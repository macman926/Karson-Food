<?php
	use Dompdf\Dompdf;
	class Document{
		public $D;
		public $data;
		public $fileInfo=[];
			//dir
			//basename
			//ext
			//body
			//css
		public function __construct($fileInfo=[],$opts=[]){
			// require_once $_SERVER['DOCUMENT_ROOT'].'/plugins/dompdf-1.2.0/autoload.inc.php';
			require_once $_SERVER['DOCUMENT_ROOT'].'/plugins/dompdf-2.0.3/autoload.inc.php';
			$this->D = new Dompdf();

			foreach($fileInfo as $f=>$g)
				$this->fileInfo[$f]=$g;

		}

		public function genDOM($a=[]){
			/**
			 * a[]
			 * 		'req','email'
			 */
			if($this->build_DOM_Data())//create ->data
				return $this->writeFile($a);
		}

		public function writeFile($a=[]){
			/**
			 * a[]
			 * 		'req','email'
			 */

			 
			if(in_array('rep',$a)){
				$filename=$this->fileInfo['basename'].".{$this->fileInfo['ext']}";
			 	if(!is_dir($this->fileInfo['dir']))
			 		mkdir($this->fileInfo['dir']);
				$fh = fopen($this->fileInfo['dir'].$filename, 'w') or die("can't open file-rep");
				fwrite($fh, $this->data);
				fclose($fh);
			}
			if(in_array('email',$a)){
				$filename=$this->fileInfo['basename'].".{$this->fileInfo['ext']}";
				$fh = fopen($this->fileInfo['emailAttDir'].$filename, 'w') or die("can't open file-email");
				fwrite($fh, $this->data);
				fclose($fh);
			}
			return true;
		}
		public 	function build_DOM_Data(){
			/*
			a[
				body
				css
			] */
			//$this->D->set_paper('letter', 'portrait');
			$this->D->loadHtml(
				"<html>"
					."<head>"
						."<style>".$this->fileInfo['css']."</style>"
					."</head>"
					."<body>"
						.$this->fileInfo['body']
					."</body>"
				."</html>"
			);
			$this->D->render();
			$this->data = $this->D->output();	
			return true;
			
		}
	}
?>