<?php
require_once('reed-solomon.inc.php'); 
require_once('encodation-200.inc.php'); 
require_once('bit-placement-bin-200.inc.php'); 

class Datamatrix 
{ 
	var $iShapeIdx = -1; 
	var $iEncodation = null; 
	var $iBitPlacement = null; 
	var $iDebug=false; 
	var $iError = 0; 
	var $iTilde = false; 
	
	function __construct($aShapeIdx=-1,$aDebug=false) 
	{ 
		$this->iBitPlacement = new BitPlacement(); 
		$this->iEncodation = new Encodation_200(); 
		$this->iShapeIdx = $aShapeIdx; 
		$this->iDebug = $aDebug; 
	} 
	
	function SetEncoding($aEncoding=ENCODING_ASCII) 
	{ 
		$this->iEncodation->iSelectSchema = $aEncoding ; 
	} 
	
	function SetSize($aShapeIdx) 
	{ 
		$this->iShapeIdx = $aShapeIdx; 
	} 
	
	function SetTilde($aFlg=true) 
	{ 
		$this->iTilde = $aFlg; 
	} 
	
	function Enc($aData,$aDebug=false) 
	{ 
		if( $this->iTilde ) 
		{ 
			$r = tilde_process($aData); 
			if( $r === false ) 
			{ 
				$this->iError = -9; 
				return false; 
			} 
			$aData = $r; 
		} 
		$data = str_split($aData); 
		$ndata = count($data); 
		$symbols = array(); 
		if( $this->iEncodation->Encode($data,$symbols,$this->iShapeIdx) === false ) 
		{ 
			$this->iError = $this->iEncodation->iError; 
			return false; 
		} 
		$this->iEncodation->AddErrorCoding(); 
		if( $this->iDebug ) 
			$this->iEncodation->_printDebugInfo(); 
			
		$outputMatrix = array(); 
		$databits = array(); 
		ByteArray2Bits($this->iEncodation->iSymbols,$databits); 
		$res = $this->iBitPlacement->Set($this->iEncodation->iSymbolShapeIdx,$databits,$outputMatrix); 
		if( $res === false ) 
		{ 
			$this->iError = $this->iBitPlacement->iError; 
			return false; 
		} 
		$pspec = new PrintSpecification(DM_TYPE_200,$data,$outputMatrix,$this->iEncodation->iSelectSchema); 
		return $pspec; 
	} 
} 
?>
