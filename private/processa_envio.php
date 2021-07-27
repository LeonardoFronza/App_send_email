<?php

   

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem {
        private $para       = null;
        private $assunto    = null;
        private $mensagem   = null;
        public $status = ['codigo_status' => null, 'descrisao_status' => ''];

        public function __get($retornaVariavel) {
            return $this->$retornaVariavel;
        }

        public function __set($variavel, $valorVariavel) {
            $this->$variavel = $valorVariavel;
        }

        public function mensagemValida() {
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }
            return true;
        }
    }

    $mensagem = new Mensagem();

    $mensagem->__set('para',$_POST['para']);
    $mensagem->__set('assunto',$_POST['assunto']);
    $mensagem->__set('mensagem',$_POST['mensagem']);

    //print_r($mensagem);

    if(!$mensagem->mensagemValida()){
        echo 'Mensagem Invalida';
        header('Location: index.php');
        //die(); #Mata o Processamento do Codigo.
    }

    #Biblioteca PHPMailer

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = false;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                             // Enable SMTP authentication
        $mail->Username = 'COLOQUE SEU EMAIL AQUI!';        // SMTP username
        $mail->Password = 'SENHA EMAIL';                    // SMTP password
        $mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                  // TCP port to connect to

        //Recipients
        $mail->setFrom('COLOQUE SEU EMAIL AQUI!', 'seu nome');
        $mail->addAddress($mensagem->__get('para'));     // Add a recipient
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = 'É necessario um Client que aceite HTML.';

        $mail->send();

        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descrisao_status'] = 'E-mail enviando com Sucesso!';

       
    } catch (Exception $e) {

        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descrisao_status'] = 'Não foi possivel enviar este e-mail! Por favor tente mais tarde! <br/ <br/> Detalhes do Erro: ' . $mail->ErrorInfo;
  
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>
    <body>
        <div class="container">
        
        <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">
                <div class="col-md-12">

                    <? if($mensagem->status['codigo_status'] == 1) { ?>

                        <div class="container">
                            <h1 class="display-4 text-success"> SUCESSO! </h1>
                            <p><?= $mensagem->status['descrisao_status']?></p>
                            <a href="index.php" class="btn btn-outline-success btn-lg mt-5 ">Voltar</a>
                        </div>

                    <? } ?>

                    <? if($mensagem->status['codigo_status'] == 2) { ?>

                        <div class="container">
                            <h1 class="display-4 text-danger"> OPAA!! </h1>
                            <p><?= $mensagem->status['descrisao_status']?></p>
                            <a href="index.php" class="btn btn-outline-danger btn-lg mt-5 ">Voltar</a>
                        </div>
                        
                    <? } ?>
                
                </div>
            
            </div>
        </div>
    </body>
    </html>