<?php 

require "../config/asteriskdb.php";

	Class DashAsterisk{
		//Constructor para instancias
		public function __construct(){

		}
                
        public function llamadas(){
			$sql="SELECT COUNT(clid) AS nllamadas FROM cdr WHERE DATE(calldate)=DATE(now()) AND dst='12'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
        public function contestadas(){
			$sql="SELECT COUNT(clid) AS ncontestadas FROM cdr WHERE DATE(calldate)=DATE(now()) AND dst='12' AND disposition='ANSWERED'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
        public function nocontestadas(){
			$sql="SELECT COUNT(clid) AS nocontestadas FROM cdr WHERE DATE(calldate)=DATE(now()) AND dst='12' AND disposition='NO ANSWER'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
        public function tcontestar(){
                        $sql="SELECT SUM(duration) AS total, SUM(billsec) as conversacion FROM cdr WHERE DATE(calldate)=DATE(now()) AND dst='12'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
        public function tconversacion(){
                        $sql="SELECT SUM(billsec) AS conversacion FROM cdr WHERE DATE(calldate)=DATE(now()) AND dst='12' AND disposition='ANSWERED'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
        public function mesllamados(){
			$sql="SELECT MONTH(calldate) AS mes, COUNT(clid) AS llamadas FROM cdr WHERE YEAR(calldate)=YEAR(now()) AND dst='12' GROUP BY MONTH(calldate)";
			return ejecutarConsulta($sql);
		}
                
        public function llamadasmes(){
			$sql="SELECT COUNT(clid) AS nllamadas FROM cdr WHERE MONTH(calldate)=MONTH(now()) AND YEAR(calldate)=YEAR(now()) AND dst='12'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
        public function contestadasmes(){
			$sql="SELECT COUNT(clid) AS ncontestadas FROM cdr WHERE MONTH(calldate)=MONTH(now()) AND YEAR(calldate)=YEAR(now()) AND dst='12' AND disposition='ANSWERED'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
        public function nocontestadasmes(){
			$sql="SELECT COUNT(clid) AS nocontestadas FROM cdr WHERE MONTH(calldate)=MONTH(now()) AND YEAR(calldate)=YEAR(now()) AND dst='12' AND disposition='NO ANSWER'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
        public function tcontestarmes(){
                        $sql="SELECT SUM(duration) AS total, SUM(billsec) as conversacion FROM cdr WHERE MONTH(calldate)=MONTH(now()) AND YEAR(calldate)=YEAR(now()) AND dst='12'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
        public function tconversacionmes(){
                        $sql="SELECT SUM(billsec) AS conversacion FROM cdr WHERE MONTH(calldate)=MONTH(now()) AND YEAR(calldate)=YEAR(now()) AND dst='12' AND disposition='ANSWERED'";
			return ejecutarConsultaSimpleFila($sql);
		}

	}
?>