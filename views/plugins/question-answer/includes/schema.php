<?php
function QA_Schema($schema, $page) {

    if($page == 'post_detail') {
        $item = get_object_current('object');

        if(!empty($item->post_type) && $item->post_type == QA_KEY) {
            $schema = [
                "@context" => 'https://schema.org/',
                "@type" => "QAPage",
                "mainEntity" => [
                    "@type" => "Question",
                    "name" => $item->title,
                    "text" => $item->title,
                    "answerCount" => 1,
                    "upvoteCount" => 1,
                    "dateCreated" => date(DATE_ATOM, strtotime($item->created)),
                    "author" => array(
                        "@type" => "Person",
                        "name" => 'Quản trị',
                    ),
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => Str::clear($item->content),
                        "dateCreated" => date(DATE_ATOM, strtotime($item->created)),
                        "upvoteCount" => 1337,
                        "url" => Url::current(),
                        "author" => array(
                            "@type" => "Person",
                            "name" => 'Quản trị',
                        ),
                    ]
                ]
            ];
        }
    }
    return $schema;
}

add_filter('schema_render', 'QA_Schema', 10, 2);