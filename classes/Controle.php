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
                $mail->Username = 'educacao.permanente@fhfs.ba.gov.br';
                $mail->Password = 'Fh@7150';
                $mail->Port = 587;

                $mail->setFrom('educacao.permanente@fhfs.ba.gov.br');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Senha de acesso ao portal FHFS';
                $conteudo = '
                <style>
                    body {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        flex-direction: column;
                        height: auto;
                        margin: 0;
                        font-family: Arial, sans-serif;
                    }
                    .senha {
                        width: 100px;
                        border: 2px solid #000;
                        padding: 20px;
                        text-align: center;
                        margin-left: 120px;
                    }
                    .listas {
                        padding: 0;
                    }
                    ul {
                        list-style-type: disc;
                        padding-left: 20px;
                        text-align: left;
                        padding: 0;
                    }
                </style>

                <h2>Sua senha de acesso ao portal FHFS:</h2>
                <div class="senha">
                    '.$senha.'
                </div>
                
                <div class="listas">
                <h2>Documentos necessários:</h2>
                <ul>
                    <li>- Apólice de seguros</li>
                    <li>- Termo de compromisso de estagio de cada estudante</li>
                    <li>- Escala com os alunos e dias que os mesmos estarão na unidade</li>
                </ul>

                <h2>Documentos para ser anexados no cadastro dos alunos:</h2>
                <ul>
                    <li>- RG</li>
                    <li>- CPF</li>
                    <li>- Cartão de vacina atualizado (3 dozes de hepatite B, COVID, Reforço de DT)</li>
                </ul>

                <h2>Documentos para ser anexados no cadastro dos professores:</h2>
                <ul>
                    <li>- Carteira do conselho do professor</li>
                    <li>- Cartão de vacina atualizado (3 dozes de hepatite B, COVID, Reforço de DT)</li>
                </ul>
                </div></b>';
                $mail->Body = mb_convert_encoding($conteudo, 'ISO-8859-1', 'UTF-8');
                $mail->send();

                return true;
            }

        } else {
            return false;
        }

    }

    public function atualizarFaculdade($id, $nome, $cnpj, $cep, $logradouro, $numero, $complemento, $cidade, $estado, $telefone, $email)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM faculdades WHERE nome = '$nome' AND id = '$id'";
        $resposta = mysqli_query($conexao, $sqlverifica);

        if (mysqli_num_rows($resposta) > 1) {
            return false;
        } else {
            $sql = "UPDATE faculdades SET id = '$id', nome = '$nome', cnpj = '$cnpj', cep = '$cep', logradouro = '$logradouro', numero = '$numero', complemento = '$complemento'
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

    public function resposta($id, $respostaEmail, $email)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM faculdades WHERE resposta = '$respostaEmail' AND id = '$id'";
        $resposta = mysqli_query($conexao, $sqlverifica);

        if (mysqli_num_rows($resposta) > 1) {
            return false;
        } else {
            $sql = "UPDATE faculdades SET id = '$id', resposta = '$respostaEmail' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);
            if (!$resultado) {
                return false;
            } else {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'educacao.permanente@fhfs.ba.gov.br';
                $mail->Password = 'Fh@7150';
                $mail->Port = 587;

                $mail->setFrom('educacao.permanente@fhfs.ba.gov.br');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Retorno da Educação Permanente FHFS';
                $mail->Body = $respostaEmail;
                $mail->send();

                return true;
            }
        }
    }

    public function cadastrarCurso($nome, $semestre, $descricao, $duracao, $id_faculdade)
    {

        $conexao = parent::conectar();
        $sql = "INSERT INTO cursos (nome, semestre,descricao, duracao, faculdade_id) 
        VALUES (?,?,?,?,?)";
        $query = $conexao->prepare($sql);
        $query->bind_param('sssss', $nome, $semestre, $descricao, $duracao, $id_faculdade);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public function atualizarCurso($id, $nome, $semestre, $descricao, $duracao, $id_faculdade)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM cursos WHERE faculdade_id = '$id_faculdade' AND id = '$id'";
        $resposta = mysqli_query($conexao, $sqlverifica);

        if (mysqli_num_rows($resposta) > 1) {
            return false;
        } else {
            $sql = "UPDATE cursos SET id = '$id', nome = '$nome', semestre = '$semestre', descricao = '$descricao', duracao = '$duracao', faculdade_id = '$id_faculdade' WHERE id = '$id'";
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

    public function cadastrarProfessor($nome, $email, $telefone, $dataNascimento, $id_curso, $cpf, $rg, $caminhos_arquivos)
    {

        $conexao = parent::conectar();
        $sql = "INSERT INTO professores (nome, email, telefone, data_nascimento, curso_id, cpf, rg, arquivo) 
        VALUES (?,?,?,?,?,?,?,?)";
        $query = $conexao->prepare($sql);
        $query->bind_param('ssssssss', $nome, $email, $telefone, $dataNascimento, $id_curso, $cpf, $rg, $caminhos_arquivos);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public function atualizarProfessor($id, $nome, $email, $telefone, $dataNascimento, $id_curso, $cpf, $rg)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM professores WHERE nome = '$nome' AND id = '$id'";
        $resposta = mysqli_query($conexao, $sqlverifica);

        if (mysqli_num_rows($resposta) > 1) {
            return false;
        } else {
            $sql = "UPDATE professores SET id = '$id', nome = '$nome', email = '$email', telefone = '$telefone', data_nascimento = '$dataNascimento', curso_id = '$id_curso'
            , cpf = '$cpf', rg = '$rg' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);
            if (!$resultado) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function cadastrarUsuario($name, $email, $password)
    {
        $conexao = parent::conectar();
        $sqlverifica = "SELECT * FROM usuarios WHERE email = '$email'";
        $resposta = mysqli_query($conexao, $sqlverifica);

        if (mysqli_num_rows($resposta) > 0) {
            return false;
        } else {
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?,?,?)";
            $query = $conexao->prepare($sql);
            $query->bind_param('sss', $name, $email, $password);
            return $query->execute();
        }
    }

    public function arquivoAluno($id, $caminhos_arquivos)
    {
        // Validação de Entrada
        if (!is_numeric($id) || empty($caminhos_arquivos)) {
            return false;
        }

        $conexao = parent::conectar();

        if (!$conexao) {
            return false;
        }

        // Recupera os arquivos existentes da faculdade
        $sqlAluno = "SELECT arquivo FROM alunos WHERE id = '$id'";
        $resposta = mysqli_query($conexao, $sqlAluno);

        if (!$resposta || mysqli_num_rows($resposta) == 0) {
            return false;
        }

        $arquivosAluno = mysqli_fetch_assoc($resposta);
        $arquivosExistente = explode(",", $arquivosAluno['arquivo']);

        // Adiciona o novo arquivo à lista de arquivos existentes
        $arquivosExistente[] = $caminhos_arquivos;

        // Remove elementos vazios do array resultante
        $arquivosExistente = array_filter($arquivosExistente);

        // Remove duplicatas e reindexa o array
        $arquivosExistente = array_values(array_unique($arquivosExistente));

        // Atualiza o campo no banco de dados com a nova lista de arquivos
        $atualizaArquivos = implode(",", $arquivosExistente);
        $sql = "UPDATE alunos SET arquivo = '$atualizaArquivos' WHERE id = '$id'";
        $resultado = mysqli_query($conexao, $sql);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    public function arquivoProfessor($id, $caminhos_arquivos)
    {
        // Validação de Entrada
        if (!is_numeric($id) || empty($caminhos_arquivos)) {
            return false;
        }

        $conexao = parent::conectar();

        if (!$conexao) {
            return false;
        }

        // Recupera os arquivos existentes da faculdade
        $sqlProfessor = "SELECT arquivo FROM professores WHERE id = '$id'";
        $resposta = mysqli_query($conexao, $sqlProfessor);

        if (!$resposta || mysqli_num_rows($resposta) == 0) {
            return false;
        }

        $arquivosProfessor = mysqli_fetch_assoc($resposta);
        $arquivosExistente = explode(",", $arquivosProfessor['arquivo']);

        // Adiciona o novo arquivo à lista de arquivos existentes
        $arquivosExistente[] = $caminhos_arquivos;

        // Remove elementos vazios do array resultante
        $arquivosExistente = array_filter($arquivosExistente);

        // Remove duplicatas e reindexa o array
        $arquivosExistente = array_values(array_unique($arquivosExistente));

        // Atualiza o campo no banco de dados com a nova lista de arquivos
        $atualizaArquivos = implode(",", $arquivosExistente);
        $sql = "UPDATE professores SET arquivo = '$atualizaArquivos' WHERE id = '$id'";
        $resultado = mysqli_query($conexao, $sql);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    public function arquivoFaculdade($id, $caminhos_arquivos)
    {
        // Validação de Entrada
        if (!is_numeric($id) || empty($caminhos_arquivos)) {
            return false;
        }

        $conexao = parent::conectar();

        if (!$conexao) {
            return false;
        }

        // Recupera os arquivos existentes da faculdade
        $sqlFaculdade = "SELECT arquivo FROM faculdades WHERE id = '$id'";
        $resposta = mysqli_query($conexao, $sqlFaculdade);

        if (!$resposta || mysqli_num_rows($resposta) == 0) {
            return false;
        }

        $arquivosFaculdade = mysqli_fetch_assoc($resposta);
        $arquivosExistente = explode(",", $arquivosFaculdade['arquivo']);

        // Adiciona o novo arquivo à lista de arquivos existentes
        $arquivosExistente[] = $caminhos_arquivos;

        // Remove elementos vazios do array resultante
        $arquivosExistente = array_filter($arquivosExistente);

        // Remove duplicatas e reindexa o array
        $arquivosExistente = array_values(array_unique($arquivosExistente));

        // Atualiza o campo no banco de dados com a nova lista de arquivos
        $atualizaArquivos = implode(",", $arquivosExistente);
        $sql = "UPDATE faculdades SET arquivo = '$atualizaArquivos' WHERE id = '$id'";
        $resultado = mysqli_query($conexao, $sql);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }


    public function autenticarFaculdade($email, $password)
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


    public function autenticarUsuario($email, $password)
    {
        $conexao = parent::conectar();
        $passwordExistente = "";
        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
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
        $sqlFaculdade = "SELECT arquivo FROM faculdades WHERE id = '$id'";
        $resposta = mysqli_query($conexao, $sqlFaculdade);

        if (!$resposta || mysqli_num_rows($resposta) == 0) {
            return false;
        }

        $arquivosFaculdade = mysqli_fetch_assoc($resposta);
        $arquivosExistente = explode(",", $arquivosFaculdade['arquivo']);

        // Verifica se o arquivo existe na lista de arquivos
        $index = array_search($arquivo, $arquivosExistente);

        if ($index !== false) {
            // Remove o arquivo da lista de arquivos existentes
            unset($arquivosExistente[$index]);

            // Remove possíveis vírgulas extras no início e no final da lista
            $arquivosExistente = array_values($arquivosExistente);

            // Atualiza o campo no banco de dados com a nova lista de arquivos
            $atualizaArquivos = implode(",", $arquivosExistente);
            $sql = "UPDATE faculdades SET arquivo = '$atualizaArquivos' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);

            if ($resultado) {
                return true;
            } else {
                return false;
            }
        } else {
            // O arquivo não foi encontrado na lista de arquivos
            return false;
        }
    }

    public function removeArquivoAluno($id, $arquivo)
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
        $sqlAluno = "SELECT arquivo FROM alunos WHERE id = '$id'";
        $resposta = mysqli_query($conexao, $sqlAluno);

        if (!$resposta || mysqli_num_rows($resposta) == 0) {
            return false;
        }

        $arquivosAluno = mysqli_fetch_assoc($resposta);
        $arquivosExistente = explode(",", $arquivosAluno['arquivo']);

        // Verifica se o arquivo existe na lista de arquivos
        $index = array_search($arquivo, $arquivosExistente);

        if ($index !== false) {
            // Remove o arquivo da lista de arquivos existentes
            unset($arquivosExistente[$index]);

            // Remove possíveis vírgulas extras no início e no final da lista
            $arquivosExistente = array_values($arquivosExistente);

            // Atualiza o campo no banco de dados com a nova lista de arquivos
            $atualizaArquivos = implode(",", $arquivosExistente);
            $sql = "UPDATE alunos SET arquivo = '$atualizaArquivos' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);

            if ($resultado) {
                return true;
            } else {
                return false;
            }
        } else {
            // O arquivo não foi encontrado na lista de arquivos
            return false;
        }
    }

    public function removeArquivoProfessor($id, $arquivo)
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
        $sqlProfessor = "SELECT arquivo FROM professores WHERE id = '$id'";
        $resposta = mysqli_query($conexao, $sqlProfessor);

        if (!$resposta || mysqli_num_rows($resposta) == 0) {
            return false;
        }

        $arquivosProfessor = mysqli_fetch_assoc($resposta);
        $arquivosExistente = explode(",", $arquivosProfessor['arquivo']);

        // Verifica se o arquivo existe na lista de arquivos
        $index = array_search($arquivo, $arquivosExistente);

        if ($index !== false) {
            // Remove o arquivo da lista de arquivos existentes
            unset($arquivosExistente[$index]);

            // Remove possíveis vírgulas extras no início e no final da lista
            $arquivosExistente = array_values($arquivosExistente);

            // Atualiza o campo no banco de dados com a nova lista de arquivos
            $atualizaArquivos = implode(",", $arquivosExistente);
            $sql = "UPDATE professores SET arquivo = '$atualizaArquivos' WHERE id = '$id'";
            $resultado = mysqli_query($conexao, $sql);

            if ($resultado) {
                return true;
            } else {
                return false;
            }
        } else {
            // O arquivo não foi encontrado na lista de arquivos
            return false;
        }
    }




}