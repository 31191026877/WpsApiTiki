<?php
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\Grammars\Grammar;

class Qr {

    public $aggregate;

    public $columns;

    public string $from;

    public array $joins;

    public array $wheres = [];

    public $groups;

    public $havings;

    public $orders;

    public $limit;

    public $offset;

    public $unions;

    public $unionLimit;

    public $unionOffset;

    public $unionOrders;

    public $lock;

    public $category;

    public $metaQuery;

    public array $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>', '&~',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    public array $bitwiseOperators = [
        '&', '|', '^', '<<', '>>', '&~',
    ];

    public array $bindings = [
        'select' => [],
        'from' => [],
        'join' => [],
        'where' => [],
        'groupBy' => [],
        'having' => [],
        'order' => [],
        'union' => [],
        'unionOrder' => [],
    ];

    public $beforeQueryCallbacks = [];

    public \Illuminate\Database\Query\Grammars\Grammar $grammar;

    public $distinct = false;

    public function __construct(Grammar $grammar = null) {
        $this->grammar = $grammar ?: new Illuminate\Database\Query\Grammars\Grammar;
        return $this;
    }

    static public function set(): Qr {

        $qr = new self();

        $where = func_get_args();

        if(have_posts($where)) {
            if(is_numeric($where[0]) && !isset($where[1])) {
                $qr->where('id', '=', $where[0]);
            }
            else if(is_string($where[0])) {
                $columns = $where[0];
                $operator = (isset($where[1]) && isset($where[2])) ? $where[1] : '=';
                $value = (isset($where[1]) && isset($where[2])) ? $where[2] : ((isset($where[1]) && !isset($where[2])) ? $where[1] : null);
                $qr->where($columns, $operator, $value);
            }
            else if(is_array($where[0])){
                $qr->where($where);
            }
        }

        return $qr;
    }

    static public function clear($qr): object {
        $qrClear = (array)$qr;
        unset($qrClear['operators']);
        unset($qrClear['bitwiseOperators']);
        unset($qrClear['bindings']);
        unset($qrClear['grammar']);
        foreach ($qrClear as $key => $item) {
            if(empty($item)) unset($qrClear[$key]);
        }
        return (object)$qrClear;
    }

    public function select($columns = ['*']): static {
        $this->columns = [];
        $this->bindings['select'] = [];
        $columns = is_array($columns) ? $columns : func_get_args();

        foreach ($columns as $as => $column) {
            if (is_string($as) && $this->isQueryable($column)) {
                $this->selectSub($column, $as);
            } else {
                $this->columns[] = $column;
            }
        }

        return $this;
    }

    public function selectSub($query, $as): static {
        [$query, $bindings] = $this->createSub($query);
        return $this->selectRaw('('.$query.') as '.$this->grammar->wrap($as), $bindings);
    }

    public function selectRaw($expression, array $bindings = []): static {

        $this->addSelect(new Expression($expression));

        if ($bindings) {
            $this->addBinding($bindings, 'select');
        }

        return $this;
    }

    protected function createSub($query): array {
        // If the given query is a Closure, we will execute it while passing in a new
        // query instance to the Closure. This will give the developer a chance to
        // format and work with the query before we cast it to a raw SQL string.
        if ($query instanceof Closure) {
            $callback = $query;
            $callback($query = $this->forSubQuery());
        }

        return $this->parseSub($query);
    }

    protected function parseSub($query): array {

        if ($query instanceof self || $query instanceof EloquentBuilder || $query instanceof Relation) {
            $query = $this->prependDatabaseNameIfCrossQr($query);
            return [$query->toSql(), $query->getBindings()];
        } elseif (is_string($query)) {
            return [$query, []];
        } else {
            throw new InvalidArgumentException('A subquery must be a query builder instance, a Closure, or a string.');
        }
    }

    protected function prependDatabaseNameIfCrossQr($query) {
        if (1 !== 1) {
            $databaseName = $query->getConnection()->getDatabaseName();
            if (!str_starts_with($query->from, $databaseName) && !str_contains($query->from, '.')) {
                $query->from($databaseName.'.'.$query->from);
            }
        }

        return $query;
    }

    public function addSelect($column): static {

        $columns = is_array($column) ? $column : func_get_args();

        foreach ($columns as $as => $column) {
            if (is_string($as) && $this->isQueryable($column)) {
                if (is_null($this->columns)) {
                    $this->select($this->from.'.*');
                }

                $this->selectSub($column, $as);
            } else {
                $this->columns[] = $column;
            }
        }

        return $this;
    }

    public function distinct(): static {

        $columns = func_get_args();

        if (count($columns) > 0) {
            $this->distinct = is_array($columns[0]) || is_bool($columns[0]) ? $columns[0] : $columns;
        } else {
            $this->distinct = true;
        }

        return $this;
    }

    public function from($table, $as = null): static {
        if ($this->isQueryable($table)) {
            return $this->fromSub($table, $as);
        }

        $this->from = $as ? "{$table} as {$as}" : $table;

        return $this;
    }

    public function fromSub($query, $as): static {
        [$query, $bindings] = $this->createSub($query);

        return $this->fromRaw('('.$query.') as '.$this->grammar->wrapTable($as), $bindings);
    }

    public function fromRaw($expression, $bindings = []): static {
        $this->from = new Expression($expression);

        $this->addBinding($bindings, 'from');

        return $this;
    }

    public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false): static {

        $join = $this->newJoinClause($this, $type, $table);

        // If the first "column" of the join is really a Closure instance the developer
        // is trying to build a join with a complex "on" clause containing more than
        // one condition, so we'll add the join and call a Closure with the query.
        if ($first instanceof Closure) {

            $first($join);

            $this->joins[] = $join;

            $this->addBinding($join->getBindings(), 'join');
        }
        // If the column is simply a string, we can assume the join simply has a basic
        // "on" clause with a single condition. So we will just build the join with
        // this simple join clauses attached to it. There is not a join callback.
        else {

            $method = $where ? 'where' : 'on';

            $this->joins[] = $join->$method($first, $operator, $second);

            $this->addBinding($join->getBindings(), 'join');
        }

        return $this;
    }

    public function joinWhere($table, $first, $operator, $second, $type = 'inner'): static {
        return $this->join($table, $first, $operator, $second, $type, true);
    }

    public function joinSub($query, $as, $first, $operator = null, $second = null, $type = 'inner', $where = false): static {
        [$query, $bindings] = $this->createSub($query);

        $expression = '('.$query.') as '.$this->grammar->wrapTable($as);

        $this->addBinding($bindings, 'join');

        return $this->join(new Expression($expression), $first, $operator, $second, $type, $where);
    }

    public function leftJoin($table, $first, $operator = null, $second = null): static {
        return $this->join($table, $first, $operator, $second, 'left');
    }

    public function leftJoinWhere($table, $first, $operator, $second): static {
        return $this->joinWhere($table, $first, $operator, $second, 'left');
    }

    public function leftJoinSub($query, $as, $first, $operator = null, $second = null): static {
        return $this->joinSub($query, $as, $first, $operator, $second, 'left');
    }

    public function rightJoin($table, $first, $operator = null, $second = null): static {
        return $this->join($table, $first, $operator, $second, 'right');
    }

    public function rightJoinWhere($table, $first, $operator, $second): static {
        return $this->joinWhere($table, $first, $operator, $second, 'right');
    }

    public function rightJoinSub($query, $as, $first, $operator = null, $second = null): static {
        return $this->joinSub($query, $as, $first, $operator, $second, 'right');
    }

    public function crossJoin($table, $first = null, $operator = null, $second = null): static {
        if ($first) {
            return $this->join($table, $first, $operator, $second, 'cross');
        }

        $this->joins[] = $this->newJoinClause($this, 'cross', $table);

        return $this;
    }

    public function crossJoinSub($query, $as): static {
        [$query, $bindings] = $this->createSub($query);

        $expression = '('.$query.') as '.$this->grammar->wrapTable($as);

        $this->addBinding($bindings, 'join');

        $this->joins[] = $this->newJoinClause($this, 'cross', new Expression($expression));

        return $this;
    }

    protected function newJoinClause(self $parentQuery, $type, $table): JoinClauseQuery {
        return new JoinClauseQuery($parentQuery, $type, $table);
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and'): static {

        if (is_array($column)) {
            return $this->addArrayOfWheres($column, $boolean);
        }

        [$value, $operator] = $this->prepareValueAndOperator($value, $operator, func_num_args() === 2);

        if ($column instanceof Closure && is_null($operator)) {
            return $this->whereNested($column, $boolean);
        }

        // If the given operator is not found in the list of valid operators we will
        // assume that the developer is just short-cutting the '=' operators and
        // we will set the operators to '=' and set the values appropriately.
        if ($this->invalidOperator($operator)) {
            [$value, $operator] = [$operator, '='];
        }

        // If the value is "null", we will just assume the developer wants to add a
        // where null clause to the query. So, we will allow a short-cut here to
        // that method for convenience so the developer doesn't have to check.
        if (is_null($value)) {
            return $this->whereNull($column, $boolean, $operator !== '=');
        }

        $type = 'Basic';

        // If the column is making a JSON reference we'll check to see if the value
        // is a boolean. If it is, we'll add the raw boolean string as an actual
        // value to the query to ensure this is properly handled by the query.
        if (Str::contains($column, '->') && is_bool($value)) {
            $value = new Expression($value ? 'true' : 'false');

            if (is_string($column)) {
                $type = 'JsonBoolean';
            }
        }

        if ($this->isBitwiseOperator($operator)) {
            $type = 'Bitwise';
        }

        // Now that we are working with just a simple query we can put the elements
        // in our array and add the query binding to our array of bindings that
        // will be bound to each SQL statements when it is finally executed.
        $this->wheres[] = compact(
            'type', 'column', 'operator', 'value', 'boolean'
        );

        return $this;
    }

    public function removeWhere($column, $operator = null, $value = null) {

        [$value, $operator] = $this->prepareValueAndOperator($value, $operator, func_num_args() === 2);
        if($operator == null) $operator = '=';
        foreach ($this->wheres as $key => $item) {
            if($item['type'] == 'In') {
                if($item['column'] == $column && (is_array($value) && empty(array_diff($item['value'], $value)))) {
                    unset($this->wheres[$key]);
                }
                if($value == null && $item['column'] == $column) {
                    unset($this->wheres[$key]);
                }

            }
            else {
                if($item['column'] == $column && $item['operator'] == $operator && $item['value'] == $value) {
                    unset($this->wheres[$key]);
                }
                if($value == null && $item['column'] == $column && $item['operator'] == $operator) {
                    unset($this->wheres[$key]);
                }
            }
        }

        return $this;
    }

    public function isWhere($column, $operator = null, $value = null) {
        if($operator != null) [$value, $operator] = $this->prepareValueAndOperator($value, $operator, func_num_args() === 2);
        foreach ($this->wheres as $item) {
            if(!isset($item['column'])) continue;
            if($item['column'] == $column && $item['operator'] == $operator && $item['value'] == $value) {
                return true;
            }
            if($operator == null && $value == null && $item['column'] == $column) {
                return true;
            }
            if($operator == null && $item['column'] == $column && $item['value'] == $value) {
                return true;
            }
            if($value == null && $item['column'] == $column && $item['operator'] == $operator) {
                return true;
            }
        }
        return false;
    }

    public function orWhere($column, $operator = null, $value = null): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        return $this->where($column, $operator, $value, 'or');
    }

    public function whereColumn($first, $operator = null, $second = null, $boolean = 'and'): static {
        // If the column is an array, we will assume it is an array of key-value pairs
        // and can add them each as a where clause. We will maintain the boolean we
        // received when the method was called and pass it into the nested where.
        if (is_array($first)) {
            return $this->addArrayOfWheres($first, $boolean, 'whereColumn');
        }

        // If the given operator is not found in the list of valid operators we will
        // assume that the developer is just short-cutting the '=' operators and
        // we will set the operators to '=' and set the values appropriately.
        if ($this->invalidOperator($operator)) {
            [$second, $operator] = [$operator, '='];
        }

        // Finally, we will add this where clause into this array of clauses that we
        // are building for the query. All of them will be compiled via a grammar
        // once the query is about to be executed and run against the database.
        $type = 'Column';

        $this->wheres[] = compact(
            'type', 'first', 'operator', 'second', 'boolean'
        );

        return $this;
    }

    public function orWhereColumn($first, $operator = null, $second = null): static {
        return $this->whereColumn($first, $operator, $second, 'or');
    }

    public function whereRaw($sql, $bindings = [], $boolean = 'and'): static {
        $this->wheres[] = ['type' => 'raw', 'sql' => $sql, 'boolean' => $boolean];

        $this->addBinding((array) $bindings, 'where');

        return $this;
    }

    public function orWhereRaw($sql, $bindings = []): static {
        return $this->whereRaw($sql, $bindings, 'or');
    }

    public function whereIn($column, $values, $boolean = 'and', $not = false): static {
        $type = $not ? 'NotIn' : 'In';

        // If the value is a query builder instance we will assume the developer wants to
        // look for any values that exists within this given query. So we will add the
        // query accordingly so that this query is properly executed when it is run.
        if (!($values instanceof Qr) && $this->isQueryable($values)) {

            [$query, $bindings] = $this->createSub($values);

            $values = [new Expression($query)];

            $this->addBinding($bindings, 'where');
        }

        // Next, if the value is Arrayable we need to cast it to its raw array form so we
        // have the underlying array value instead of an Arrayable object which is not
        // able to be added as a binding, etc. We will then add to the wheres array.
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        $this->wheres[] = compact('type', 'column', 'values', 'boolean');

        return $this;
    }

    public function orWhereIn($column, $values): static {
        return $this->whereIn($column, $values, 'or');
    }

    public function whereNotIn($column, $values, $boolean = 'and'): static {
        return $this->whereIn($column, $values, $boolean, true);
    }

    public function orWhereNotIn($column, $values): static {
        return $this->whereNotIn($column, $values, 'or');
    }

    public function whereIntegerInRaw($column, $values, $boolean = 'and', $not = false): static {
        $type = $not ? 'NotInRaw' : 'InRaw';

        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        foreach ($values as &$value) {
            $value = (int) $value;
        }

        $this->wheres[] = compact('type', 'column', 'values', 'boolean');

        return $this;
    }

    public function orWhereIntegerInRaw($column, $values): static {
        return $this->whereIntegerInRaw($column, $values, 'or');
    }

    public function whereIntegerNotInRaw($column, $values, $boolean = 'and'): static {
        return $this->whereIntegerInRaw($column, $values, $boolean, true);
    }

    public function orWhereIntegerNotInRaw($column, $values): static {
        return $this->whereIntegerNotInRaw($column, $values, 'or');
    }

    public function whereNull($columns, $boolean = 'and', $not = false): static {

        $type = $not ? 'NotNull' : 'Null';

        foreach (Arr::wrap($columns) as $column) {
            $this->wheres[] = compact('type', 'column', 'boolean');
        }

        return $this;
    }

    public function orWhereNull($column): static {
        return $this->whereNull($column, 'or');
    }

    public function whereNotNull($columns, $boolean = 'and'): static {
        return $this->whereNull($columns, $boolean, true);
    }

    public function whereBetween($column, array $values, $boolean = 'and', $not = false): static {

        $type = 'between';

        $this->wheres[] = compact('type', 'column', 'values', 'boolean', 'not');

        $this->addBinding(array_slice($this->cleanBindings(Arr::flatten($values)), 0, 2), 'where');

        return $this;
    }

    public function whereBetweenColumns($column, array $values, $boolean = 'and', $not = false): static {

        $type = 'betweenColumns';

        $this->wheres[] = compact('type', 'column', 'values', 'boolean', 'not');

        return $this;
    }

    public function orWhereBetween($column, array $values): static {
        return $this->whereBetween($column, $values, 'or');
    }

    public function orWhereBetweenColumns($column, array $values): static {
        return $this->whereBetweenColumns($column, $values, 'or');
    }

    public function whereNotBetween($column, array $values, $boolean = 'and'): static {
        return $this->whereBetween($column, $values, $boolean, true);
    }

    public function whereNotBetweenColumns($column, array $values, $boolean = 'and'): static {
        return $this->whereBetweenColumns($column, $values, $boolean, true);
    }

    public function orWhereNotBetween($column, array $values): static {
        return $this->whereNotBetween($column, $values, 'or');
    }

    public function orWhereNotBetweenColumns($column, array $values): static {
        return $this->whereNotBetweenColumns($column, $values, 'or');
    }

    public function orWhereNotNull($column): static {
        return $this->whereNotNull($column, 'or');
    }

    public function whereDate($column, $operator, $value = null, $boolean = 'and'): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        $value = $this->flattenValue($value);

        if ($value instanceof DateTimeInterface) {
            $value = $value->format('Y-m-d');
        }

        return $this->addDateBasedWhere('Date', $column, $operator, $value, $boolean);
    }

    public function orWhereDate($column, $operator, $value = null): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        return $this->whereDate($column, $operator, $value, 'or');
    }

    public function whereTime($column, $operator, $value = null, $boolean = 'and'): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        $value = $this->flattenValue($value);

        if ($value instanceof DateTimeInterface) {
            $value = $value->format('H:i:s');
        }

        return $this->addDateBasedWhere('Time', $column, $operator, $value, $boolean);
    }

    public function orWhereTime($column, $operator, $value = null): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        return $this->whereTime($column, $operator, $value, 'or');
    }

    public function whereDay($column, $operator, $value = null, $boolean = 'and'): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        $value = $this->flattenValue($value);

        if ($value instanceof DateTimeInterface) {
            $value = $value->format('d');
        }

        if (! $value instanceof Expression) {
            $value = str_pad($value, 2, '0', STR_PAD_LEFT);
        }

        return $this->addDateBasedWhere('Day', $column, $operator, $value, $boolean);
    }

    public function orWhereDay($column, $operator, $value = null): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        return $this->whereDay($column, $operator, $value, 'or');
    }

    public function whereMonth($column, $operator, $value = null, $boolean = 'and'): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        $value = $this->flattenValue($value);

        if ($value instanceof DateTimeInterface) {
            $value = $value->format('m');
        }

        if (! $value instanceof Expression) {
            $value = str_pad($value, 2, '0', STR_PAD_LEFT);
        }

        return $this->addDateBasedWhere('Month', $column, $operator, $value, $boolean);
    }

    public function orWhereMonth($column, $operator, $value = null): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        return $this->whereMonth($column, $operator, $value, 'or');
    }

    public function whereYear($column, $operator, $value = null, $boolean = 'and'): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        $value = $this->flattenValue($value);

        if ($value instanceof DateTimeInterface) {
            $value = $value->format('Y');
        }

        return $this->addDateBasedWhere('Year', $column, $operator, $value, $boolean);
    }

    public function orWhereYear($column, $operator, $value = null): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        return $this->whereYear($column, $operator, $value, 'or');
    }

    protected function addDateBasedWhere($type, $column, $operator, $value, $boolean = 'and'): static {
        $this->wheres[] = compact('column', 'type', 'boolean', 'operator', 'value');

        if (! $value instanceof Expression) {
            $this->addBinding($value, 'where');
        }

        return $this;
    }

    public function whereNested(Closure $callback, $boolean = 'and'): static {
        call_user_func($callback, $query = new Qr());

        return $this->addNestedWhereQuery($query, $boolean);
    }

    public function forNestedWhere(): static {
        return $this->newQuery()->from($this->from);
    }

    public function addNestedWhereQuery($query, $boolean = 'and'): static {
        if (count($query->wheres)) {
            $type = 'Nested';

            $this->wheres[] = compact('type', 'query', 'boolean');

            $this->addBinding($query->getRawBindings()['where'], 'where');
        }

        return $this;
    }

    protected function whereSub($column, $operator, Closure $callback, $boolean): static {
        $type = 'Sub';

        // Once we have the query instance we can simply execute it so it can add all
        // of the sub-select's conditions to itself, and then we can cache it off
        // in the array of where clauses for the "main" parent query instance.
        call_user_func($callback, $query = $this->forSubQuery());

        $this->wheres[] = compact(
            'type', 'column', 'operator', 'query', 'boolean'
        );

        $this->addBinding($query->getBindings(), 'where');

        return $this;
    }

    public function whereExists(Closure $callback, $boolean = 'and', $not = false): static {

        $query = $this->forSubQuery();

        // Similar to the sub-select clause, we will create a new query instance so
        // the developer may cleanly specify the entire exists query and we will
        // compile the whole thing in the grammar and insert it into the SQL.
        call_user_func($callback, $query);

        return $this->addWhereExistsQuery($query, $boolean, $not);
    }

    public function orWhereExists(Closure $callback, $not = false): static {
        return $this->whereExists($callback, 'or', $not);
    }

    public function whereNotExists(Closure $callback, $boolean = 'and'): static {
        return $this->whereExists($callback, $boolean, true);
    }

    public function orWhereNotExists(Closure $callback): static {
        return $this->orWhereExists($callback, true);
    }

    public function addWhereExistsQuery(self $query, $boolean = 'and', $not = false): static {
        $type = $not ? 'NotExists' : 'Exists';

        $this->wheres[] = compact('type', 'query', 'boolean');

        $this->addBinding($query->getBindings(), 'where');

        return $this;
    }

    public function whereRowValues($columns, $operator, $values, $boolean = 'and'): static {
        if (count($columns) !== count($values)) {
            throw new InvalidArgumentException('The number of columns must match the number of values');
        }

        $type = 'RowValues';

        $this->wheres[] = compact('type', 'columns', 'operator', 'values', 'boolean');

        $this->addBinding($this->cleanBindings($values));

        return $this;
    }

    public function orWhereRowValues($columns, $operator, $values): static {
        return $this->whereRowValues($columns, $operator, $values, 'or');
    }

    public function whereJsonContains($column, $value, $boolean = 'and', $not = false): static {
        $type = 'JsonContains';

        $this->wheres[] = compact('type', 'column', 'value', 'boolean', 'not');

        if (! $value instanceof Expression) {
            $this->addBinding($this->grammar->prepareBindingForJsonContains($value));
        }

        return $this;
    }

    public function orWhereJsonContains($column, $value): static {
        return $this->whereJsonContains($column, $value, 'or');
    }

    public function whereJsonDoesntContain($column, $value, $boolean = 'and'): static {
        return $this->whereJsonContains($column, $value, $boolean, true);
    }

    public function orWhereJsonDoesntContain($column, $value): static {
        return $this->whereJsonDoesntContain($column, $value, 'or');
    }

    public function whereJsonLength($column, $operator, $value = null, $boolean = 'and'): static {
        $type = 'JsonLength';

        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        $this->wheres[] = compact('type', 'column', 'operator', 'value', 'boolean');

        if (! $value instanceof Expression) {
            $this->addBinding((int) $this->flattenValue($value));
        }

        return $this;
    }

    public function orWhereJsonLength($column, $operator, $value = null): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        return $this->whereJsonLength($column, $operator, $value, 'or');
    }

    public function dynamicWhere($method, $parameters): static {
        $finder = substr($method, 5);

        $segments = preg_split(
            '/(And|Or)(?=[A-Z])/', $finder, -1, PREG_SPLIT_DELIM_CAPTURE
        );

        // The connector variable will determine which connector will be used for the
        // query condition. We will change it as we come across new boolean values
        // in the dynamic method strings, which could contain a number of these.
        $connector = 'and';

        $index = 0;

        foreach ($segments as $segment) {
            // If the segment is not a boolean connector, we can assume it is a column's name
            // and we will add it to the query as a new constraint as a where clause, then
            // we can keep iterating through the dynamic method string's segments again.
            if ($segment !== 'And' && $segment !== 'Or') {
                $this->addDynamic($segment, $connector, $parameters, $index);

                $index++;
            }

            // Otherwise, we will store the connector so we know how the next where clause we
            // find in the query should be connected to the previous ones, meaning we will
            // have the proper boolean connector to connect the next where clause found.
            else {
                $connector = $segment;
            }
        }

        return $this;
    }

    protected function addDynamic($segment, $connector, $parameters, $index) {
        // Once we have parsed out the columns and formatted the boolean operators we
        // are ready to add it to this query as a where clause just like any other
        // clause on the query. Then we'll increment the parameter index values.
        $bool = strtolower($connector);

        $this->where(Str::snake($segment), '=', $parameters[$index], $bool);
    }

    public function whereFullText($columns, $value, array $options = [], $boolean = 'and'): static {
        $type = 'Fulltext';

        $columns = (array) $columns;

        $this->wheres[] = compact('type', 'columns', 'value', 'options', 'boolean');

        $this->addBinding($value);

        return $this;
    }

    public function orWhereFullText($columns, $value, array $options = []): static {
        return $this->whereFulltext($columns, $value, $options, 'or');
    }

    public function whereByCategory($columns): static {
        $this->category['object'] = $columns;
        return $this;
    }
    public function categoryType($columns, $value = 0): static {
        $this->category['type']  = $columns;
        $this->category['value'] = $value;
        return $this;
    }

    public function groupBy(...$groups): static {
        foreach ($groups as $group) {
            $this->groups = array_merge((array) $this->groups, Arr::wrap($group));
        }

        return $this;
    }

    public function groupByRaw($sql, array $bindings = []): static {
        $this->groups[] = new Expression($sql);

        $this->addBinding($bindings, 'groupBy');

        return $this;
    }

    public function having($column, $operator = null, $value = null, $boolean = 'and'): static {
        $type = 'Basic';

        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        // If the given operator is not found in the list of valid operators we will
        // assume that the developer is just short-cutting the '=' operators and
        // we will set the operators to '=' and set the values appropriately.
        if ($this->invalidOperator($operator)) {
            [$value, $operator] = [$operator, '='];
        }

        if ($this->isBitwiseOperator($operator)) {
            $type = 'Bitwise';
        }

        $this->havings[] = compact('type', 'column', 'operator', 'value', 'boolean');

        if (! $value instanceof Expression) {
            $this->addBinding($this->flattenValue($value), 'having');
        }

        return $this;
    }

    public function orHaving($column, $operator = null, $value = null): static {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        return $this->having($column, $operator, $value, 'or');
    }

    public function havingBetween($column, array $values, $boolean = 'and', $not = false): static {
        $type = 'between';

        $this->havings[] = compact('type', 'column', 'values', 'boolean', 'not');

        $this->addBinding(array_slice($this->cleanBindings(Arr::flatten($values)), 0, 2), 'having');

        return $this;
    }

    public function havingRaw($sql, array $bindings = [], $boolean = 'and'): static {
        $type = 'Raw';

        $this->havings[] = compact('type', 'sql', 'boolean');

        $this->addBinding($bindings, 'having');

        return $this;
    }

    public function orHavingRaw($sql, array $bindings = []): static {
        return $this->havingRaw($sql, $bindings, 'or');
    }

    public function orderBy($column, $direction = 'asc'): static {

        if ($this->isQueryable($column)) {

            [$query, $bindings] = $this->createSub($column);

            $column = new Expression('('.$query.')');

            $this->addBinding($bindings, $this->unions ? 'unionOrder' : 'order');
        }

        $direction = strtolower($direction);

        if (! in_array($direction, ['asc', 'desc'], true)) {
            throw new InvalidArgumentException('Order direction must be "asc" or "desc".');
        }

        $this->{$this->unions ? 'unionOrders' : 'orders'}[] = [
            'column' => $column,
            'direction' => $direction,
        ];

        return $this;
    }

    public function orderByDesc($column): static {
        return $this->orderBy($column, 'desc');
    }

    public function latest($column = 'created_at'): static {
        return $this->orderBy($column, 'desc');
    }

    public function oldest($column = 'created_at'): static {
        return $this->orderBy($column, 'asc');
    }

    public function inRandomOrder($seed = ''): static {
        return $this->orderByRaw($this->grammar->compileRandom($seed));
    }

    public function orderByRaw($sql, $bindings = []): static {
        $type = 'Raw';

        $this->{$this->unions ? 'unionOrders' : 'orders'}[] = compact('type', 'sql');

        $this->addBinding($bindings, $this->unions ? 'unionOrder' : 'order');

        return $this;
    }

    public function skip($value): static {
        return $this->offset($value);
    }

    public function offset($value): static {

        $property = $this->unions ? 'unionOffset' : 'offset';

        $this->$property = max(0, (int) $value);

        return $this;
    }


    public function take($value): static {
        return $this->limit($value);
    }

    public function limit($value): static {
        $property = $this->unions ? 'unionLimit' : 'limit';

        if ($value >= 0) {
            $this->$property = ! is_null($value) ? (int) $value : null;
        }

        return $this;
    }

    public function prepareValueAndOperator($value, $operator, $useDefault = false): array {
        if ($useDefault) {
            return [$operator, '='];
        } elseif ($this->invalidOperatorAndValue($operator, $value)) {
            throw new InvalidArgumentException('Illegal operator and value combination.');
        }

        return [$value, $operator];
    }

    protected function invalidOperatorAndValue($operator, $value): bool {
        return is_null($value) && in_array($operator, $this->operators) && ! in_array($operator, ['=', '<>', '!=']);
    }

    protected function invalidOperator($operator): bool {
        return ! in_array(strtolower($operator), $this->operators, true) &&
            ! in_array(strtolower($operator), [], true);
    }

    protected function isBitwiseOperator($operator): bool {
        return in_array(strtolower($operator), $this->bitwiseOperators, true) ||
            in_array(strtolower($operator), [], true);
    }

    public function getBindings(): array {
        return Arr::flatten($this->bindings);
    }

    public function getRawBindings(): array {
        return $this->bindings;
    }

    public function setBindings(array $bindings, $type = 'where'): static {
        if (! array_key_exists($type, $this->bindings)) {
            throw new InvalidArgumentException("Invalid binding type: {$type}.");
        }

        $this->bindings[$type] = $bindings;

        return $this;
    }

    public function addBinding($value, $type = 'where'): static {

        if (! array_key_exists($type, $this->bindings)) {
            throw new InvalidArgumentException("Invalid binding type: {$type}.");
        }

        if (is_array($value)) {
            $this->bindings[$type] = array_values(array_map(
                [$this, 'castBinding'],
                array_merge($this->bindings[$type], $value),
            ));
        } else {
            $this->bindings[$type][] = $this->castBinding($value);
        }

        return $this;
    }

    public function cleanBindings(array $bindings): array {
        return collect($bindings)
            ->reject(function ($binding) {
                return $binding instanceof Expression;
            })
            ->map([$this, 'castBinding'])
            ->values()
            ->all();
    }

    public function castBinding($value) {
        if (function_exists('enum_exists') && $value instanceof BackedEnum) {
            return $value->value;
        }
        return $value;
    }

    protected function isQueryable($value): bool {
        return $value instanceof self || $value instanceof EloquentBuilder || $value instanceof Relation || $value instanceof Closure;
    }

    protected function forSubQuery(): static {
        return $this->newQuery();
    }

    public function newQuery(): static {
        return new static($this->grammar);
    }

    protected function flattenValue($value) {
        return is_array($value) ? head(Arr::flatten($value)) : $value;
    }

    public function getGrammar(): \Illuminate\Database\Query\Grammars\Grammar {
        return $this->grammar;
    }

    public function toSql() {
        $this->applyBeforeQueryCallbacks();
        return $this->grammar->compileSelect($this);
    }

    public function applyBeforeQueryCallbacks() {
        foreach ($this->beforeQueryCallbacks as $callback) {
            $callback($this);
        }
        $this->beforeQueryCallbacks = [];
    }

    public function setMetaQueryRelation($compare = 'and') {
        $this->metaQuery['relation'] = $compare;
        return $this;
    }

    public function setMetaQuery($key, $value, $compare = '=') {
        $this->metaQuery[] = [
            'key'       => $key,
            'value'     => $value,
            'compare'   => $compare,
        ];
        return $this;
    }

    static function convert($args, $metaTable = 'metabox') {

        if($args instanceof Qr) return $args;

        if(is_numeric($args)) return $args;

        if(!is_array($args)) return $args;

        if(empty($args)) return new Qr();

        $qr = new Qr();

        if(!empty($args['where'])) {
            if(is_array($args['where'])){
                foreach ($args['where'] as $keyWhere => $valueWhere) {
                    $keyWhere = explode(' ', $keyWhere);
                    if(count($keyWhere) == 2) {
                        $qr->where($keyWhere[0], $keyWhere[1], $valueWhere);
                    }
                    else {
                        $qr->where($keyWhere[0], $valueWhere);
                    }
                }
            }
            if(is_string($args['where'])) {
                $qr->whereRaw($args['where']);
            }
        }

        if(!empty($args['where_in'])) {

            if(empty($args['where_in']['data'])) return false;

            $qr->whereIn($args['where_in']['field'], $args['where_in']['data']);
        }

        if(!empty($args['where_like']) && is_array($args['where_like'])) {
            foreach($args['where_like'] as $columnName => $columnValue) {
                $qr->where($columnName, 'like', '%'.$columnValue[0].'%');
            }

            if(!empty($args['where_or_like']) && is_array($args['where_or_like'])) {
                foreach($args['where_or_like'] as $columnName => $columnValue) {
                    $qr->orWhere($columnName, 'like', '%'.$columnValue[0].'%');
                }
            }
        }

        if(!empty($args['where_category'])) {
            $qr->whereByCategory($args['where_category']);
        }

        if(!empty($args['params'])) {
            if(!empty($args['params']['select'])) $select = $args['params']['select'];
            if(!empty($args['params']['limit'])) $limit = $args['params']['limit'];
            if(isset($args['params']['start'])) $offset = $args['params']['start'];
            if(!empty($args['params']['orderby'])) $orderBy = $args['params']['orderby'];
        }

        if(!empty($metaTable != null)) {

            if(!empty($args['meta_key']) || !empty($args['meta_value'])) {
                $compare 	= (!empty($args['meta_compare'])) ? strtolower($args['meta_compare']) : '=';
                $meta_key 	= (!empty($args['meta_key'])) ? (($compare == 'like' || $compare == 'not like') ? '%'.$args['meta_key'].'%' : $args['meta_key']) : '';
                $meta_value = (!empty($args['meta_value'])) ? (($compare == 'like' || $compare == 'not like') ? '%'.$args['meta_value'].'%' : $args['meta_value']) : '';
                $subQuery   = Qr::set()->select($metaTable.".object_id");
                if(!empty($meta_key)) $subQuery->where("meta_key", $compare, $meta_key);
                if(!empty($meta_value)) $subQuery->where("meta_value", $compare, $meta_value);
                $qr->whereIn('id', $subQuery);
            }

            if(!empty($args['meta_query'])) {
                $metaQuery = $args['meta_query'];
                $relation 	= 'and';
                if(!empty($metaQuery['relation'])) {
                    $relation = strtolower($metaQuery['relation']); unset($metaQuery['relation']);
                }
                if($relation != 'and' || $relation != 'or') $relation = 'and';
                $table  = CLE_PREFIX.$metaTable;
                $sql = 'SELECT `'.$table.'`.`object_id` FROM `'.$table.'`';
                foreach ($metaQuery as $key => $data) {
                    $sql .= ' INNER JOIN `'.$table.'` AS mt'.$key.' ON ( `'.$table.'`.id = mt'.$key.'.id ) ';
                }
                $sql .= 'WHERE 1=1 AND ';
                $sql .= ' (( ';
                foreach ($metaQuery as $key => $data) {
                    $mt = 'mt'.$key.'.';
                    $sql .= '(';
                    $sql .= (!empty($data['key'])) ? $mt.'`meta_key` = \''.$data['key'].'\'' : '';
                    $compare = (!empty($data['compare'])) ? strtolower($data['compare']) : '=';
                    if(!empty($data['value'])) {
                        if(!empty($data['key'])) $sql .= ' and ';
                        if($compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=') {
                            $compare = ($compare == '==') ? '' : ' '.$compare ;
                            $sql .= $mt.'`meta_value`'.$compare.' \''.$data['value'].'\'';
                        }
                        else if( $compare == 'like' || $compare == 'not like') {
                            $sql .= $mt.'`meta_value` '.$compare.' \'%'.$data['value'].'%\'';
                        }
                    }
                    $sql .= ') '.$relation.' ';
                }
                $sql = trim($sql, ' '.$relation.' ');
                $sql .= '))';
                $qr->whereRaw('id in ('.$sql.')');
            }
        }

        if(isset($args['slug'])) $qr->where('slug', $args['slug']);

        if(isset($args['id'])) $qr->where('id', $args['id']);

        if(!empty($args['select'])) $select = $args['select'];

        if(!empty($args['limit'])) $limit = $args['limit'];

        if(isset($args['start'])) $offset = $args['start'];

        if(!empty($args['orderby'])) $orderBy = $args['orderby'];

        if(!empty($select)) {
            $select = explode(',', $select);
            foreach ($select as $key => $item) {
                $select[$key] = trim($item);
            }
            $qr->select(...$select);
        }

        if(isset($limit)) $qr->limit($limit);

        if(isset($offset)) $qr->offset($offset);

        if(!empty($orderBy)) {
            if($orderBy == 'rand()') {
                $qr->orderByRaw(DB::raw('RAND()'));
            }
            else {
                $orderBy = explode(',', $orderBy);
                foreach ($orderBy as $order) {
                    if(Str::is('* asc*', $order) || Str::is('* desc*', $order)) {
                        $order = explode(' ', trim($order));
                        $qr->orderBy(strtolower($order[0]), strtoupper($order[1]));
                    }
                    else $qr->orderBy(trim($order));
                }
            }
        }

        return $qr;
    }
}
class JoinClauseQuery extends Qr {

    public $type;

    public $table;

    protected $parentClass;


    public function __construct(Qr $parentQuery, $type, $table) {
        $this->type = $type;
        $this->table = $table;
        $this->parentClass = get_class($parentQuery);
        parent::__construct();
    }

    public function on($first, $operator = null, $second = null, $boolean = 'and'): JoinClauseQuery {
        if ($first instanceof Closure) {
            return $this->whereNested($first, $boolean);
        }

        return $this->whereColumn($first, $operator, $second, $boolean);
    }

    public function orOn($first, $operator = null, $second = null): JoinClauseQuery {
        return $this->on($first, $operator, $second, 'or');
    }
}
class Model {
    static string $table = '';
    static function get($args = null) {
        $args = static::handleParams($args);
        if(!$args instanceof Qr) return [];
        return apply_filters('get_'.static::$table, model(static::$table)->get($args));
    }
    static function getBy($field, $value) {
        return apply_filters('get_'.static::$table.'_by', static::get(Qr::set(Str::clear($field), Str::clear($value))), $field, $value );
    }
    static function gets($args = null) {
        $args = static::handleParams($args);
        if($args == null) $args = Qr::set();
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        return apply_filters('gets_'.static::$table, model(static::$table)->gets($args));
    }
    static function getsBy( $field, $value, $params = [] ) {
        return apply_filters('gets_'.static::$table.'_by', static::gets(Qr::set(Str::clear($field), Str::clear($value))), $field, $value );
    }
    static function max($column, $args) {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->max($column, $args));
    }
    static function min($column, $args) {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->min($column, $args));
    }
    static function avg($column, $args) {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->avg($column, $args));
    }
    static function sum($column, $args) {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->sum($column, $args));
    }
    static function exists($args): bool {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->exists($args));
    }
    static function doesntExist($args): bool {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->doesntExist($args));
    }
    static function count($args = null) {
        $args = static::handleParams($args);
        if(!$args instanceof Qr) return 0;
        return apply_filters('count_'.static::$table, model(static::$table)->count($args));
    }
    static function toSql($args = null) {
        $args = static::handleParams($args);
        if(!$args instanceof Qr) return '';
        return apply_filters('sql_'.static::$table, model(static::$table)->toSql($args));
    }
    static function update($update, $args) {

        if(!have_posts($update)) {
            return new SKD_Error('invalid_update', __('Không có trường dữ liệu nào được cập nhật.'));
        }

        if(!have_posts($args)) {
            return new SKD_Error( 'invalid_update', __('Không có điều kiện cập nhật.'));
        }

        if(is_array($args))$args = Qr::convert($args);

        $object = static::gets($args);

        if(!have_posts($object)) return 0;

        return apply_filters('update_'.static::$table, model(static::$table)->update($update, $args), $update, $args);
    }
    static function getMeta($objectId, $key, $single) {
        return Metadata::get(static::$table, $objectId, $key, $single);
    }
    static function updateMeta($objectId, $meta_key, $meta_value) {
        return Metadata::update(static::$table, $objectId, $meta_key, $meta_value);
    }
    static function deleteMeta($objectId, $meta_key, $meta_value) {
        return Metadata::delete(static::$table, $objectId, $meta_key, $meta_value);
    }
    static function handleParams($args = null) {
        if(is_array($args)) {
            $args = Qr::convert($args);
            if(!$args) return $args;
        }
        if(is_numeric($args)) $args = Qr::set('id', $args);

        if($args === null) return Qr::set();

        return $args;
    }
}
if(!function_exists('get_model')){
    function get_model($model = 'home'): MY_Model {
        if(!class_exists('MY_Model')) require_once(FCPATH.APPPATH.'core/MY_Model.php');
        return new MY_Model();
    }
}
if(!function_exists('model')){
    /**
     * @since 6.0.0
     */
    function model($table = null) {
        if($table == null) return get_model()->database;
        return get_model()->setTable($table);
    }
}