<?php

    class ConsultaExameMedico {
        private $codConsulta;
        private $codExame;
        private $codMedico;
        private $inicio;
        private $termino;

        private $dbUsuario;
        private $dbSenha;

        //SETTERS
        public function setCodConsulta($codigo){
            $this->codConsulta = $codigo;
        }
        public function setCodExame($codigo){
            $this->codExame = $codigo;
        }
        public function setCodMedico($codigo){
            $this->codMedico = $codigo;
        }
        public function setInicio($data){
            $this->inicio = $data;
        }
        public function setTermino($data){
            $this->termino = $data;
        }
        public function setDBUsuario($usuario){
            $this->dbUsuario = $usuario;
        }
        public function setDBSenha($senha){
            $this->dbSenha = $senha;
        }

        //GETTERS
        public function getCodConsulta(){
            return $this->codConsulta;
        }
        public function getCodExame(){
            return $this->codExame;
        }
        public function getCodMedico(){
            return $this->codMedico;
        }
        public function getInicio(){
            return $this->inicio;
        }
        public function getTermino(){
            return $this->termino;
        }

        //CRUD
        public function lista(){
            try {

                include_once('../../database.class.php');

                $db = new database();
                $db->setUsuario($this->dbUsuario);
                $db->setSenha($this->dbSenha);

                $conexao = $db->conecta_mysql();

                $sqlLista = "SELECT E.codExame, E.nome AS exame, E.descricao, E.codigo,
                                    C.codConsulta, C.dataHora, P.nome AS paciente, P.cpf, 
                                    Em.codEmpresa, Em.nome AS empresa,
                                    M.codMedico, M.nome AS medico, 
                                    CEM.inicio, CEM.termino
                             FROM consulta_exame_medico CEM 
                                INNER JOIN consulta C 
                                ON CEM.codConsulta = C.codConsulta 
                                INNER JOIN exame E 
                                ON CEM.codExame = E.codExame
                                LEFT JOIN medico M 
                                ON CEM.codMedico = M.codMedico
                                INNER JOIN paciente P 
                                ON C.codPaciente = P.codPaciente
                                INNER JOIN empresa Em 
                                ON C.codEmpresa = Em.codEmpresa
                             ORDER BY C.dataHora DESC";
                $conexao->exec('SET NAMES utf8');
                $stmtLista = $conexao->prepare($sqlLista);
                $stmtLista->execute();

                $lista = $stmtLista->fetchALL(PDO::FETCH_ASSOC);
                $response = Array();

                $keys = array_keys($lista);
                $size = count($lista);
                
                for ($i = 0; $i < $size; $i++) {
                    $key = $keys[$i];

                    if($lista[$key]["codConsulta"] == null) continue;
                    
                    $aux->codConsulta = $lista[$key]["codConsulta"];
                    $aux->dataHora = $lista[$key]["dataHora"];
                    $aux->paciente = $lista[$key]["paciente"];
                    $aux->cpf = $lista[$key]["cpf"];
                    $aux->codEmpresa = $lista[$key]["codEmpresa"];
                    $aux->empresa = $lista[$key]["empresa"];

                    $procedimento->codExame = $lista[$key]["codExame"];
                    $procedimento->exame = $lista[$key]["exame"];
                    $procedimento->descricao = $lista[$key]["descricao"];
                    $procedimento->codigo = $lista[$key]["codigo"];
                    $procedimento->codMedico = $lista[$key]["codMedico"];
                    $procedimento->medico = $lista[$key]["medico"];
                    $procedimento->inicio = $lista[$key]["inicio"];
                    $procedimento->termino = $lista[$key]["termino"];

                    $aux->procedimentos = array(clone $procedimento);

                    unset($lista[$key]);

                    foreach($lista as $key_aux => $row_aux) {
                        
                        if(
                            $aux->codConsulta == $row_aux["codConsulta"] && 
                            !in_array($row_aux["codExame"], $aux->procedimentos, true)
                            ) {
                                $aux_procedimento->codExame = $row_aux["codExame"];
                                $aux_procedimento->exame = $row_aux["exame"];
                                $aux_procedimento->descricao = $row_aux["descricao"];
                                $aux_procedimento->codigo = $row_aux["codigo"];
                                $aux_procedimento->codMedico = $row_aux["codMedico"];
                                $aux_procedimento->medico = $row_aux["medico"];
                                $aux_procedimento->inicio = $row_aux["inicio"];
                                $aux_procedimento->termino = $row_aux["termino"];

                                array_push($aux->procedimentos, clone $aux_procedimento);
                                unset($lista[$key_aux]);
                        }
                    }

                    array_push($response, clone $aux);
                }

                return $response;
                // return $lista;
            } catch (PDOException $e) {
                echo "Erro: ".$e->getMessage();
            }
        }

        public function listaJSON(){
            echo json_encode($this->lista());
        }
        public function create(){

            try {

                include('../../database.class.php');

                $db = new database();
                $db->setUsuario($this->dbUsuario);
                $db->setSenha($this->dbSenha);

                $conexao = $db->conecta_mysql();

                $sqlCreate = "INSERT INTO consulta_exame_medico(codConsulta,codExame) VALUES(?,?)";
                $conexao->exec('SET NAMES utf8');
                $stmtCreate = $conexao->prepare($sqlCreate);
                $stmtCreate->bindParam(1,$this->codConsulta);
                $stmtCreate->bindParam(2,$this->codExame);
                echo($stmtCreate->execute());

            } catch (PDOException $e) {
                echo "Erro: ".$e->getMessage();
            }
        }
        public function read($campo_principal,$codigo){

            try {

                include('../../database.class.php');

                $db = new database();
                $db->setUsuario($this->dbUsuario);
                $db->setSenha($this->dbSenha);

                $conexao = $db->conecta_mysql();

                $sqlRead = "SELECT  E.codExame, E.nome AS exame, E.descricao, E.codigo,
                                    C.codConsulta, C.dataHora, P.nome AS paciente, P.cpf, 
                                    Em.codEmpresa, Em.nome AS empresa,
                                    M.codMedico, M.nome, 
                                    CEM.inicio, CEM.termino
                            FROM consulta_exame_medico CEM 
                                INNER JOIN consulta C 
                                ON CEM.codConsulta = C.codConsulta 
                                INNER JOIN exame E 
                                ON CEM.codExame = E.codExame
                                LEFT JOIN medico M 
                                ON CEM.codMedico = M.codMedico
                                INNER JOIN paciente P 
                                ON C.codPaciente = P.codPaciente
                                INNER JOIN empresa Em 
                                ON C.codEmpresa = Em.codEmpresa
                            WHERE CEM.$campo_principal = ?";
                $conexao->exec('SET NAMES utf8');
                $stmtRead = $conexao->prepare($sqlRead);
                $stmtRead->bindParam(1,$codigo);
                $stmtRead->execute();

                $dados = $stmtRead->fetchALL(PDO::FETCH_ASSOC);
                echo json_encode($dados);

            } catch (PDOException $e) {
                echo "Erro: ".$e->getMessage();
            }
        }
        public function update(){

            try {

                include('../../database.class.php');

                $db = new database();
                $db->setUsuario($this->dbUsuario);
                $db->setSenha($this->dbSenha);

                $conexao = $db->conecta_mysql();

                $sqlUpdate = "UPDATE consulta_exame_medico SET codMedico = ?, inicio = ?, termino = ? 
                              WHERE codConsulta = ? AND codExame = ?";
                $conexao->exec('SET NAMES utf8');
                $stmtUpdate = $conexao->prepare($sqlUpdate);
                $stmtUpdate->bindParam(1,$this->codMedico);
                $stmtUpdate->bindParam(2,$this->inicio);
                $stmtUpdate->bindParam(3,$this->termino);
                $stmtUpdate->bindParam(4,$this->codConsulta);
                $stmtUpdate->bindParam(5,$this->codExame);
                echo($stmtUpdate->execute());

            } catch (PDOException $e){
                echo "Erro: ".$e->getMessage();
            }
        }
        public function delete(){

            try {

                include('../../database.class.php');

                $db = new database();
                $db->setUsuario($this->dbUsuario);
                $db->setSenha($this->dbSenha);

                $conexao = $db->conecta_mysql();

                $sqlDelete = "DELETE FROM consulta_exame_medico 
                              WHERE codConsulta = ? AND codExame = ? AND codMedico = NULL 
                                AND inicio = NULL AND termino = NULL";
                $conexao->exec('SET NAMES utf8');
                $stmtDelete = $conexao->prepare($sqlDelete);
                $stmtDelete->bindParam(1,$this->codConsulta);
                $stmtDelete->bindParam(2,$this->codExame);
                echo($stmtDelete->execute());

            } catch (PDOException $e) {
                echo "Erro: ".$e->getMessage();
            }
        }
    }
?>