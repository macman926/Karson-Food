<?php

//class Calendar
class Calendar extends DBConn
{

	public $Y=0;
	public $m=0;
	public $daysInMonth=0;
	public $dateAry=[];
	public $calTbl="";
	private $optAry=[];
	public $date_fieldname="dt";
	public function __construct($Y,$m,$optAry=[]){
		$this->Y=$Y;
		$this->m=$m;
		$this->daysInMonth=$this->getDaysInMonth($this->Y,$this->m);
		$this->optAry=$optAry;
	}//constructor

	public function buildCalTbl($header_style='labelOnly'){
		/**
				dateAry['2022-03-01'=>[],'2022-03-02'=>[],]
		 */
		// die("<pre>".print_r($this->dateAry,1));
		$w=[//wrappers
			'calWrapper'=>['<table id="calView" class="'.($this->optAry['calClass']??'').'">','</table>'],
			'hdr_wrapper'=>["<tr class='CalHdrWrapper %s ".($this->optAry['calCtrlClass']??'')."'>","</tr>"],			
			'hdr_days'=>['<th class="calHdrDays">','</th>'],
			'week_wrapper'=>["<tr class='calWkWrapper %s'>","</tr>"],
			'inactive_day'=>["<td class='calDate inactive_day' %s>","</td>"],
			'active_day'=>["<td class='calDate active_day %s' %s>","</td>"],			
		];

		$dayOfWeek_firstDayOfMonth=date('w',strtotime("{$this->Y}-{$this->m}-01"));//0-6
		$dayOfWeek_lastDayOfMonth=date('w',strtotime("{$this->Y}-{$this->m}-{$this->daysInMonth}"));
		$week_num=1;
		$tbl=$w['calWrapper'][0];
		
		$hdrMonths=['January','February','March','April','May','June','July','August','September','October','November','December',];
		$hdrYears=[];
		for($x=$this->Y-5;$x<=$this->Y+5;$x++)
			$hdrYears[]=$x;
		//////////////
		//build controls
		$control_html='';

		//////////////


		switch($header_style){
			case 'select':
				$controls=['Y'=>'','m'=>''];
				$ctr=0;
				foreach($hdrMonths as $m){
					$ctr++;
					$controls['m'].="<option value='".str_pad($ctr,2,"0",STR_PAD_LEFT)."' ".($this->m==str_pad($ctr,2,"0",STR_PAD_LEFT)?' selected ':'').">".$m."</option>";
				}
				foreach($hdrYears as $Y)
					$controls['Y'].="<option value='{$Y}' ".($this->Y==$Y?' selected ':'').">".$Y."</option>";
				$tbl.=vsprintf($w['hdr_wrapper'][0],['CalHdrControls'])."<th colspan='7' class='calControls'>";
					$tbl.="<button type='button' class='btn btn-sm btn-unique btnMonth' id='btnMonthPrev' value='".($this->getDate('prevMonth'))."'> < </button>";
					$tbl.="<select id='cal_m' class='custom-select custom-select-sm browser-default w-auto mr-2'>{$controls['m']}</select>";
					$tbl.="<select id='cal_Y' class='custom-select custom-select-sm browser-default w-auto mr-2'>{$controls['Y']}</select>";
					$tbl.="<button type='button' class='btn btn-sm btn-unique' id='calChgGoBtn'>Go</button>";
					$tbl.="<button type='button' class='btn btn-sm btn-unique btnMonth' id='btnMonthNext' value='".($this->getDate('nextMonth'))."'> > </button>";
				$tbl.="</th>".$w['hdr_wrapper'][1];
				break;
			case 'monthBtn':
				$tbl.=vsprintf($w['hdr_wrapper'][0],['CalHdrControls'])."<th colspan='100%' class='calControls'>";
				$tbl.="<button type='button' class='btn btn-sm btn-unique btnMonth' id='btnMonthPrev' value='".($this->getDate('prevMonth'))."'> < </button>";
				$tbl.="<div id='CalHeaderLbl'>".date('F Y',strtotime("{$this->Y}-{$this->m}-01"))."</div>";
				
				$tbl.="<button type='button' class='btn btn-sm btn-unique btnMonth' id='btnMonthNext' value='".($this->getDate('nextMonth'))."'> > </button>";
				$tbl.="</th>".$w['hdr_wrapper'][1];
				break;
			case 'labelOnly':
				$tbl.=vsprintf($w['hdr_wrapper'][0],['CalHdrControls'])."<th colspan='100%' class='calControls'>";
				$tbl.="<div id='CalHeaderLbl'>".date('F Y',strtotime("{$this->Y}-{$this->m}-01"))."</div>";
				
				$tbl.="</th>".$w['hdr_wrapper'][1];
				break;
		}
		//////////////		
		$hdrDays=['Sun','Mon','Tue','Wed','Thu','Fri','Sat',];
		$tbl.=vsprintf($w['hdr_wrapper'][0],['CalHdrDOW']);
		foreach($hdrDays as $d)
			$tbl.=$w['hdr_days'][0].$d.$w['hdr_days'][1];
		$tbl.=$w['hdr_wrapper'][1];
		//////////////
		///prev month blank days before first day of month
		if($dayOfWeek_firstDayOfMonth!=0)
			$tbl.=vsprintf($w['week_wrapper'][0],['weeknum_'.($week_num++)]);
		for($x=0;$x<$dayOfWeek_firstDayOfMonth;$x++)
			$tbl.=vsprintf($w['inactive_day'][0],['']).$w['inactive_day'][1];


		///////////////////
		// days in months
		// echo '<pre>'.print_r($this->dateAry,1);exit;
		for($x=1;$x<=$this->daysInMonth;$x++){
			$classAddon='';
			$currDate=$this->Y."-".$this->m."-".str_pad($x,2,"0",STR_PAD_LEFT);
			$classAddon.=$currDate==date('Y-m-d')?' isToday':'';
			$dayOfWeek_thisDate=date('w',strtotime($currDate));//0-6	
			if($dayOfWeek_thisDate==0)//sun
				$tbl.=vsprintf($w['week_wrapper'][0],['weeknum_'.($week_num++)]);
			//////
			$tbl.=vsprintf($w['active_day'][0],[' '.$classAddon,'']);//add class,properties
			$tbl.="<div class='dayNum'>".str_pad($x,2,"0",STR_PAD_LEFT)."</div><hr class='dayNumHR'>";

			if(isset($this->dateAry[$currDate]['output'])){
				$tbl.="<div class='dayDataWrapper'>"
					.$this->dateAry[$currDate]['output']
					."</div>";
			}
			
			$tbl.=vsprintf($w['active_day'][1],[]);
			//////
			if($dayOfWeek_thisDate==6)//sat
				$tbl.=$w['week_wrapper'][1];
		}
		///////////////////
		// blank days after last day of month
		for($x=$dayOfWeek_lastDayOfMonth+1;$x<=6;$x++)
			$tbl.=vsprintf($w['inactive_day'][0],['']).$w['inactive_day'][1];	
		$tbl.=$w['week_wrapper'][1];		
		$tbl.=$w['calWrapper'][1];

		$this->calTbl=$tbl;
		return $this;
	} //fn buildCalTbl

	public function getDaysInMonth($Y,$m){
		return cal_days_in_month(CAL_GREGORIAN, $m, $Y);
	}

	public function setDateAry($res,$o=[]){
		switch($o['c']??''){
			case 'def':
			case '':
				$this->dateAry=[];
				$date_fieldname=$o['date_fieldname']??$this->date_fieldname;
				//////////////
				//fill recAry with data
				// die("<pre>".print_r($res,1));
				foreach($res as $d){
					if(!isset($this->dateAry[$d[$date_fieldname]]))
						$this->dateAry[$d[$date_fieldname]]=[
							'recs'=>[],
							'output'=>"",
						];
					$this->dateAry[$d[$date_fieldname]]['recs'][]=$d;
				}
				/////////
				// echo '<pre>'.print_r($this->dateAry,1);exit;
				foreach($this->dateAry as $dt=>$z){
					foreach($z['recs'] as $itm)
						$this->dateAry[$dt]['output'].="<div class='dayDataItm ".($this->optAry['dataItmClass']??'')."  ".($itm['additionalClasses']??'')."'>"
							.$itm['Name']
						."</div>";
				}

				break;
		}
	}
	public function getDate($v){
		switch($v??''){
			case 'prevMonth':
				return date('Ym',strtotime("{$this->Y}-{$this->m}-01 -1 months"));				
				break;
			case 'nextMonth':
				return date('Ym',strtotime("{$this->Y}-{$this->m}-01 +1 months"));
				break;
		}
	}

}



?>
