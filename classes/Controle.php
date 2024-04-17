<?php
include "ConexaoBanco.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Controle extends ConexaoBanco
{


    public function cadastrarFaculdade($nome, $cep, $logradouro, $numero, $complemento, $cidade, $estado, $telefone, $email)
    {

        function gerarSenha($tamanho = 8, $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
        {
            $senha = '';
            $quantidade_caracteres = strlen($caracteres) - 1;
            for ($i = 0; $i < $tamanho; $i++) {
                $senha .= $caracteres[rand(0, $quantidade_caracteres)];
            }
            return $senha;
        }

        $conexao = parent::conectar();
        $sql = "INSERT INTO faculdades (nome, cep, logradouro, numero, complemento, cidade, estado, telefone, email) 
        VALUES (?,?,?,?,?,?,?,?,?)";
        $query = $conexao->prepare($sql);
        $query->bind_param('sssssssss', $nome, $cep, $logradouro, $numero, $complemento, $cidade, $estado, $telefone, $email);

        if ($query->execute()) {

            $senha = gerarSenha();
            $senha_faculdade = password_hash($senha, PASSWORD_DEFAULT);
            $sql_senha = "INSERT INTO usuarios_faculdade (nome, email, senha) VALUES (?,?,?)";
            $query_senha = $conexao->prepare($sql_senha);
            $query_senha->bind_param('sss', $nome, $email, $senha_faculdade);

            if ($query_senha->execute()) {

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'setic@fhfs.ba.gov.br';
                $mail->Password = 'cERBERUS@150';
                $mail->Port = 587;

                $mail->setFrom('setic@fhfs.ba.gov.br');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Senha gerada para acesso ao portal';
                $mail->Body = '
                <b>Senha: </b>' . $senha;
                $mail->send();

                return true;
            }

        } else {
            return false;
        }

    }

    public function atualizarFaculdade($id, $nome, $cep, $logradouro, $numero, $complemento, $cidade, $estado, $telefone, $email)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM faculdades WHERE nome = '$nome' AND id = '$id'";
        $resposta = mysqli_query($conexao, $sqlverifica);

        if (mysqli_num_rows($resposta) > 1) {
            return false;
        } else {
            $sql = "UPDATE faculdades SET id = '$id', nome = '$nome', cep = '$cep', logradouro = '$logradouro', numero = '$numero', complemento = '$complemento'
            , cidade = '$cidade', estado = '$estado'
            , telefone = '$telefone', email = '$email' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);
            if (!$resultado) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function cadastrarCurso($nome, $descricao, $duracao, $id_faculdade)
    {

        $conexao = parent::conectar();
        $sql = "INSERT INTO cursos (nome, descricao, duracao, faculdade_id) 
        VALUES (?,?,?,?)";
        $query = $conexao->prepare($sql);
        $query->bind_param('ssss', $nome, $descricao, $duracao, $id_faculdade);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public function atualizarCurso($id, $nome, $descricao, $duracao, $id_faculdade)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM cursos WHERE faculdade_id = '$id_faculdade' AND id = '$id'";
        $resposta = mysqli_query($conexao, $sqlverifica);

        if (mysqli_num_rows($resposta) > 1) {
            return false;
        } else {
            $sql = "UPDATE cursos SET id = '$id', nome = '$nome', descricao = '$descricao', duracao = '$duracao', faculdade_id = '$id_faculdade' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);
            if (!$resultado) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function cadastrarAluno($nome, $email, $telefone, $dataNascimento, $id_curso, $cpf, $rg, $caminhos_arquivos)
    {

        $conexao = parent::conectar();
        $sql = "INSERT INTO alunos (nome, email, telefone, data_nascimento, curso_id, cpf, rg, arquivo) 
        VALUES (?,?,?,?,?,?,?,?)";
        $query = $conexao->prepare($sql);
        $query->bind_param('ssssssss', $nome, $email, $telefone, $dataNascimento, $id_curso, $cpf, $rg, $caminhos_arquivos);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public function atualizarAluno($id, $nome, $email, $telefone, $dataNascimento, $id_curso, $cpf, $rg)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM alunos WHERE nome = '$nome' AND id = '$id'";
        $resposta = mysqli_query($conexao, $sqlverifica);

        if (mysqli_num_rows($resposta) > 1) {
            return false;
        } else {
            $sql = "UPDATE alunos SET id = '$id', nome = '$nome', email = '$email', telefone = '$telefone', data_nascimento = '$dataNascimento', curso_id = '$id_curso'
            , cpf = '$cpf', rg = '$rg' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);
            if (!$resultado) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function arquivoAluno($id, $caminhos_arquivos)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM alunos WHERE id = '$id'";
        $resposta = mysqli_query($conexao, $sqlverifica);
        $arquivosAlunos = mysqli_fetch_assoc($resposta);

        $arquivos = $arquivosAlunos['arquivo'] . $caminhos_arquivos;

        if (mysqli_num_rows($resposta) > 1) {
            return false;
        } else {
            $sql = "UPDATE alunos SET id = '$id', arquivo = '$arquivos' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);
            if (!$resultado) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function arquivoFaculdade($id, $caminhos_arquivos)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM faculdades WHERE id = '$id'";
        $resposta = mysqli_query($conexao, $sqlverifica);
        $arquivosAlunos = mysqli_fetch_assoc($resposta);

        $arquivos = $arquivosAlunos['arquivo'] . $caminhos_arquivos;

        if (mysqli_num_rows($resposta) > 1) {
            return false;
        } else {
            $sql = "UPDATE faculdades SET id = '$id', arquivo = '$arquivos' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);
            if (!$resultado) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function autenticar($email, $password)
    {
        $conexao = parent::conectar();
        $passwordExistente = "";
        $sql = "SELECT * FROM usuarios_faculdade WHERE email = '$email'";
        $resposta = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resposta) > 0) {
            $passwordExistente = mysqli_fetch_array($resposta);
            $passwordExistente = $passwordExistente['senha'];

            if (password_verify($password, $passwordExistente)) {
                $_SESSION['email'] = $email;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function atualizarChamado($id, $id_usuario, $status, $infoimpressora, $resposta, $problema, $acompanhante, $atendimento, $setor, $nome, $descricao)
    {
        $conexao = parent::conectar();
        $sql = "UPDATE chamados SET id = '$id', id_usuario = '$id_usuario', status = '$status', infoimpressora = '$infoimpressora', resposta = '$resposta', problema = '$problema', acompanhante = '$acompanhante', atendimento = '$atendimento' 
            WHERE id = '$id'";
        $atualizado = mysqli_query($conexao, $sql);

        $sqlaltera = "INSERT INTO historico_chamado (status, infoimpressora, resposta, problema, id_chamado, id_usuario, acompanhante, atendimento) VALUES (?,?,?,?,?,?,?,?)";
        $query = $conexao->prepare($sqlaltera);
        $query->bind_param('ssssssss', $status, $infoimpressora, $resposta, $problema, $id, $id_usuario, $acompanhante, $atendimento);

        if ($atualizado && $query->execute()) {

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Username = 'setic@fhfs.ba.gov.br';
            $mail->Password = 'cERBERUS@150';
            $mail->Port = 587;

            $sqlEmail = "SELECT * FROM setores WHERE name = '$setor'";
            $sqlEmailSetor = mysqli_query($conexao, $sqlEmail);
            $emailSetor = mysqli_fetch_assoc($sqlEmailSetor);

            $sqlUsuarioTipo = "SELECT * FROM usuarios WHERE id = '$id_usuario'";
            $usuarioTipo = mysqli_query($conexao, $sqlUsuarioTipo);
            $tipoUsuario = mysqli_fetch_assoc($usuarioTipo);


            if ($sqlEmail == true) {
                $mail->setFrom('setic@fhfs.ba.gov.br');
                $mail->addAddress($emailSetor['email']);
                $mail->isHTML(true);
                $mail->Subject = 'Resposta de chamado SETIC';
                $mail->Body = '
                <b>Chamado aberto por: </b>' . $nome . '<br><br>
                <b>Problema: </b>' . $descricao . '<br><br>
                <b>Resposta: </b>' . $resposta . '<br><br>
                <b>Status: </b>' . $status . '<br><br>
                <b>Atendente: </b>' . $tipoUsuario['username'] . '<br><br>
                Favor n&atilde;o responder este e-mail autom&aacute;tico.';
                $mail->send();
            }

            return true;
        } else {
            return false;
        }
    }

    public function removeArquivoFaculdade($id, $arquivo)
    {
        // Validação de Entrada
        if (!is_numeric($id) || empty($arquivo)) {
            return false;
        }

        $conexao = parent::conectar();

        if (!$conexao) {
            return false;
        }

        // Recupera os arquivos existentes da faculdade
        $sqlFaculdade = "SELECT * FROM faculdades WHERE id = '$id'";
        $resposta = mysqli_query($conexao, $sqlFaculdade);

        if (!$resposta || mysqli_num_rows($resposta) == 0) {
            return false;
        }

        $arquivosFaculdade = mysqli_fetch_assoc($resposta);
        $arquivosExistente = explode(",", $arquivosFaculdade['arquivo']);

        // Remove o arquivo da lista de arquivos existentes
        $index = array_search($arquivo, $arquivosExistente);

        if ($index !== false) {
            unset($arquivosExistente[$index]);
        } else {
            return false;
        }

        // Atualiza o campo no banco de dados com a nova lista de arquivos
        $atualizaArquivos = implode(",", $arquivosExistente);
        $sql = "UPDATE faculdades SET arquivo = '$atualizaArquivos' WHERE id = '$id'";
        $resultado = mysqli_query($conexao, $sql);

        if ($resultado) {
            return true;
        } else {
            // Se houver um erro ao atualizar o banco de dados, retorne false
            return false;
        }
    }




}