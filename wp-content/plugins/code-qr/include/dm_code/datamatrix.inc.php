<?php
require_once('dm-utils.inc.php');
require_once('datamatrix-200.inc.php');
require_once('printspec.inc.php');
require_once('backend.inc.php');
DEFINE('DATAMATRIX_TYPE140',1);
DEFINE('DATAMATRIX_TYPE200',2);
DEFINE('DMAT_AUTO',-1);
DEFINE('DMAT_10x10',0);
DEFINE('DMAT_12x12',1);
DEFINE('DMAT_14x14',2);
DEFINE('DMAT_16x16',3);
DEFINE('DMAT_18x18',4);
DEFINE('DMAT_20x20',5);
DEFINE('DMAT_22x22',6);
DEFINE('DMAT_24x24',7);
DEFINE('DMAT_26x26',8);
DEFINE('DMAT_32x32',9);
DEFINE('DMAT_36x36',10);
DEFINE('DMAT_40x40',11);
DEFINE('DMAT_44x44',12);
DEFINE('DMAT_48x48',13);
DEFINE('DMAT_52x52',14);
DEFINE('DMAT_64x64',15);
DEFINE('DMAT_72x72',16);
DEFINE('DMAT_80x80',17);
DEFINE('DMAT_88x88',18);
DEFINE('DMAT_96x96',19);
DEFINE('DMAT_104x104',20);
DEFINE('DMAT_120x120',21);
DEFINE('DMAT_132x132',22);
DEFINE('DMAT_144x144',23);
DEFINE('DMAT_8x18',24);
DEFINE('DMAT_8x32',25);
DEFINE('DMAT_12x26',26);
DEFINE('DMAT_12x36',27);
DEFINE('DMAT_16x36',28);
DEFINE('DMAT_16x48',29);
DEFINE('DMAT140_AUTO',-1);
DEFINE('DMAT140_9x9',0+50);
DEFINE('DMAT140_11x11',1+50);
DEFINE('DMAT140_13x13',2+50);
DEFINE('DMAT140_15x15',3+50);
DEFINE('DMAT140_17x17',4+50);
DEFINE('DMAT140_19x19',5+50);
DEFINE('DMAT140_21x21',6+50);
DEFINE('DMAT140_23x23',7+50);
DEFINE('DMAT140_25x25',8+50);
DEFINE('DMAT140_27x27',9+50);
DEFINE('DMAT140_29x29',10+50);
DEFINE('DMAT140_31x31',11+50);
DEFINE('DMAT140_33x33',12+50);
DEFINE('DMAT140_35x35',13+50);
DEFINE('DMAT140_37x37',14+50);
DEFINE('DMAT140_39x39',15+50);
DEFINE('DMAT140_41x41',16+50);
DEFINE('DMAT140_43x43',17+50);
DEFINE('DMAT140_45x45',18+50);
DEFINE('DMAT140_47x47',19+50);
DEFINE('DMAT140_49x49',20+50);
DEFINE('ENCODING_C40',0);
DEFINE('ENCODING_TEXT',1);
DEFINE('ENCODING_X12',2);
DEFINE('ENCODING_EDIFACT',3);
DEFINE('ENCODING_ASCII',4);
DEFINE('ENCODING_BASE256',5);
DEFINE('ENCODING_AUTO',6);
DEFINE('ENCODING_BASE11',0);
DEFINE('ENCODING_BASE27',1);
DEFINE('ENCODING_BASE37',2);
DEFINE('ENCODING_BASE41',3);
DEFINE('ENCODING_BYTE',5); 

class DatamatrixFactory 
{ 
	function Create($aSize=-1,$aType=DATAMATRIX_TYPE200,$aDebug=false) 
	{ 
		switch( $aType ) 
		{ 
			case DATAMATRIX_TYPE140: 
				return new Datamatrix_140($aSize,$aDebug); 
				break; 
			case DATAMATRIX_TYPE200: 
				return new Datamatrix($aSize,$aDebug); 
				break; 
			default: 
				return false; 
		} 
	} 
} 

class DatamatrixErrorStr {

	function Get($aErrNo) {

		$iErrStr = array ( 
			1 => 'Data is too long to fit specified symbol size', 
			2 => 'The BASE256 data is too long to fit available symbol size', 
			3 => 'Data must have at least three characters for C40 encodation', 
			4 => 'Data must have at least three characters for TEXT encodation', 
			5 => 'Internal error: (-5) Trying to read source data past the end', 
			6 => 'Internal error: (-6) Trying to look ahead in data past the end', 
			7 => 'Internal error: (-7) Logic error in TEXT/C40 encodation (impossible branch)', 
			8 => 'The given data can not be encoded using X12 encodation.', 
			9 => 'The "tilde" encoded data is not valid.', 
			10 => 'Data must have at least three characters for X12 encodation', 
			11 => 'Specified data can not be encoded with datamatrix 000 140', 
			12 => 'Can not create image', 
			13 => 'Invalid color specification', 
			14 => 'Internal error: (-14) Index for 140 bit placement matrix out of bounds', 
			15 => 'This PHP installation does not support the chosen image encoding format', 
			16 => 'Internal error: (-16) Cannot instantiate ReedSolomon', 
			20 => 'The specification for shape of matrix is out of bounds (0,29)', 
			21 => 'Cannot open the data file specifying bit placement for Datamatrix 200', 
			22 => 'Datafile for bit placement is corrupt, crc checks fails.', 
			23 => 'Internal error: (-23) Output matrice is not big enough for mapping matrice', 
			24 => 'Internal error: (-24) Bit sequence to be placed is too short for the chosen output matrice', 
			25 => 'Internal error: (-25) Shape index out of bounds for bit placement', 
			26 => 'Cannot open the data file specifying bit placement for Datamatrix 140', 
			30 => 'The symbol size specified for ECC140 type Datamatrix is not valid', 
			31 => 'Data is to long to fit into any available matrice size for datamatrix 140', 
			32 => 'Internal error: (-32) Cannot instantiate MasterRandom', 
			33 => 'Internal error: (-33) Failed to randomize 140 bit stream', 
			99 => 'EDIFACT encodation not implemented'
		); 
		
		if( !in_array(abs($aErrNo),array_keys($iErrStr)) ) {

			return "Error number '$aErrNo' does not exist !"; } else { return $iErrStr[abs($aErrNo)]; 
		} 
	} 
} 
?>
