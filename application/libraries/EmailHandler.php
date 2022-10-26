<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EmailHandler {

    private $name;

    private $from;

    private $cc;

    private $subject;

    private $address;

    private $user       = '';

    private $pass       = '';

    private $port       = 465;

    private $host       = 'smtp.googlemail.com';

    private $encryption = 'tls';

    private $protocol   = 'smtp';

    private $template = '';

    private $templateValue = [];

    function __construct(){

        $this->setUser(Option::get('smtp-user'));

        $this->setPass(Option::get('smtp-pass'));

        $this->setHost(Option::get('smtp-server'));

        $this->setPort(Option::get('smtp-port'));

        $this->setEncryption(Option::get('smtp-encryption'));
    }

    public function setUser($user ='') {
        $this->user = $user;
        return $this;
    }

    public function setPass($pass ='') {
        $this->pass = $pass;
        return $this;
    }

    public function setPort($port ='') {
        $this->port = $port;
        return $this;
    }

    public function setHost($host ='') {
        $this->host = $host;
        return $this;
    }

    public function setEncryption($encryption ='') {
        if(empty($encryption)) $encryption = 'tls';
        $this->encryption = $encryption;
        return $this;
    }

    public function setProtocol($protocol ='') {
        $this->protocol = $protocol;
        return $this;
    }

    public function setSubject($subject ='') {
        $this->subject = $subject;
        return $this;
    }

    public function setEmailTemplate($path ='') {
        $this->template = $path;
        return $this;
    }

    public function setEmailTemplateValues($config = []) {
        $this->templateValue = $config;
        return $this;
    }

    public function setVariableValues($config) {

        $this->name = (!empty($config['fullname'])) ? Str::clear($config['fullname']) : $this->name;

        $this->name = (!empty($config['name'])) ? Str::clear($config['name']) : $this->name;

        $this->from = (!empty($config['from_email'])) ? Str::clear($config['from_email']) : $this->from;

        $this->from = (!empty($config['from'])) ? Str::clear($config['from']) : $this->from;

        $this->cc = (!empty($config['cc'])) ? Str::clear($config['cc']) : $this->cc;

        $this->subject = (!empty($config['subject'])) ? Str::clear($config['subject']) : $this->subject;

        $this->address = (!empty($config['to_email'])) ? Str::clear($config['to_email']) : $this->address;

        $this->address = (!empty($config['address'])) ? Str::clear($config['address']) : $this->address;

        return $this;
    }

    public function renderEmailTemplate() {
        if(strlen($this->template) <= PHP_MAXPATHLEN && is_file($this->template) && file_exists($this->template)) {
            $this->template = file_get_contents($this->template);
        }
        if(have_posts($this->templateValue)) {
            foreach ($this->templateValue as $key => $label) {
                $this->template = str_replace('{{' . $key . '}}', $label, $this->template);
                $this->template = str_replace('{{ ' . $key . ' }}', $label, $this->template);
            }
        }
        return $this->template;
    }

    public function sending() {

        $this->renderEmailTemplate();

        if(empty($this->template)) {
            return new SKD_Error('email_template', 'Nội dung email không được để trống.');
        }

        if(empty($this->subject)) {
            return new SKD_Error('email_subject', 'Tiêu đề email không được để trống.');
        }

        if(empty($this->from)) {
            return new SKD_Error('email_from', 'Email gửi đi không được để trống.');
        }

        if(empty($this->address)) {
            return new SKD_Error('email_address', 'Địa chỉ nhận không được để trống.');
        }

        if( $this->host == 'smtp.gmail.com' || $this->host == 'ssl://smtp.googlemail.com') {
            return $this->mailerSend();
        } else {
            return $this->ciSend();
        }
    }

    public function ciSend() {

        $ci = get_instance();

        $config = array('protocol' 	=> $this->protocol, 'smtp_host' => $this->host, 'smtp_port' => $this->port, 'smtp_user' => $this->user, 'smtp_pass' => $this->pass, 'mailtype' 	=> 'html', 'charset' 	=> 'utf-8', 'newline' 	=> "\r\n",);

        $ci->load->library('email', $config);

        $ci->email->set_newline("\r\n");

        $ci->email->from($this->from, $this->name);

        $ci->email->to($this->address);

        $ci->email->subject($this->subject);

        $ci->email->message($this->template);

        stream_context_set_default([
            'ssl' => [
                'verify_peer'		=> false,
                'verify_peer_name' 	=> false,
            ]
        ]);

        if (!$ci->email->send()) {
            return new SKD_Error('email_send', $ci->email->print_debugger());
        }
        else {
            return true;
        }
    }

    public function mailerSend() {

        include_once "phpmailer/class.phpmailer.php";
        include_once "phpmailer/class.smtp.php";
        include_once "phpmailer/class.pop3.php";

        $mail = new PHPMailer();
        //Khai báo gửi mail bằng SMTP
        $mail->IsSMTP();

        $mail->SMTPDebug   = 0;
        $mail->CharSet     = "utf-8";
        $mail->Debugoutput = "html"; // Lỗi trả về hiển thị với cấu trúc HTML
        $mail->Host        = $this->host; //host smtp để gửi mail
        $mail->Port        = $this->port; // cổng để gửi mail
        $mail->SMTPSecure  = $this->encryption; //Phương thức mã hóa thư - ssl hoặc tls
        $mail->SMTPAuth    = true; //Xác thực SMTP
        $mail->Username    = $this->user; // Tên đăng nhập tài khoản Gmail
        $mail->Password    = $this->pass;//$config['smtp_pass']; //Mật khẩu của gmail
        $mail->SetFrom( $this->from , $this->name); // Thông tin người gửi
        $mail->AddReplyTo( $this->from,"Reply");// Ấn định email sẽ nhận khi người dùng reply lại.
        $mail->AddAddress( $this->address, "");//Email của người nhận
        $mail->Subject     = $this->subject; //Tiêu đề của thư
        $mail->MsgHTML( $this->template ); //Nội dung của bức thư.

        //Tiến hành gửi email và kiểm tra lỗi
        if(!$mail->Send()) {
            return new SKD_Error('email_send', "Có lỗi khi gửi mail: " . $mail->ErrorInfo);
        } else {
            return true;
        }
    }

    static function send($content, $subject, $args = []) {

        $EmailHandler = new EmailHandler();

        if(isset($args['templateValue']) && have_posts($args['templateValue'])) {
            
            $EmailHandler->setEmailTemplateValues($args['templateValue']);
        }

        return $EmailHandler->setEmailTemplate($content)
            ->setSubject($subject)
            ->setVariableValues($args)
            ->sending();
    }
}