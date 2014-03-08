<?
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin(7);
$reservationNo = isset($_REQUEST["reservationNo"]) ? $_REQUEST["reservationNo"] : "";

$resv = new ReservationObject($reservationNo);
$m_helper = new MemberHelper();
$member = $m_helper->getMemberByuserid($_REQUEST['userid']);
$mission = $m_helper->getMissionInfoByuserid($_REQUEST['userid']);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><META content="IE=11.0000" http-equiv="X-UA-Compatible">
<META http-equiv="content-type" content="text/html; charset=utf-8">
<TITLE> God Lovehouse 관리자화면 </TITLE>
<LINK href="css/print.css" rel="stylesheet" type="text/css" media="print">
<LINK href="css/design.css" rel="stylesheet" type="text/css" media="screen">
<META name="GENERATOR" content="MSHTML 11.00.9600.16518"></HEAD>	 
<BODY id="popup">
<H1 style="float:center;" align="center">사용신청서</H1> <span class="btn1g" style="float: right;"><a href="#" onclick="window.print()"> 프린트하기 </a></span>
<TABLE class="write2 mt20">
  <COLGROUP>
  <COL width="15%">
  <COL width="35%">
  <COL width="15%">
  <COL width="35%"></COLGROUP>
  <TBODY>
  <TR>
    <TH>선교관  / 방</TH>
	<TD colspan="3"><?=$resv->houseName?> / <?=$resv->roomName?></TD>
  </TR>
  <TR>
    <TH>성명<BR>(닉네임)</TH>
    <TD><?=$member->name?><br>(<?=$member->Nick?>)</TD>
    <TH>출생년도<BR>(성별)</TH>
    <TD><?=$mission->BirthYear?>년&nbsp;(<INPUT type="checkbox"> 남, 
      <INPUT type="checkbox"> 여)</TD></TR>
  <TR>
    <TH>회원연락처<BR>(유선전화)</TH>
    <TD><?=implode('-',$member->mobile)?><br>(<?=implode('-',$member->phone)?>)</TD>
    <TH>회원 이메일</TH>
    <TD><?=implode('@',$member->email)?></TD></TR>
  <TR>
    <TH>선교지<BR></TH>
    <TD><?=$mission->Nation?></TD>
    <TH>파송단체(교회)</TH>
    <TD><?=$mission->Church?></TD></TR>
  <TR>
    <TH>파송년도<BR></TH>
    <TD><?=$mission->SentYear?></TD>
    <TH>파송단체연락처</TH>
    <TD><?=$mission->ChurchContact?></TD></TR>
  <TR>
    <TH>예약 일자<BR>(예약일수)</TH>
    <TD><?=date("Y. m. d", $resv->startdate)?> ~ <?=date("Y. m. d", $resv->enddate)?> 
      <BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(총 : <?=$resv->duration?>일) 
    </TD>
    <TH>예약인원수</TH>
    <TD>(총 : <?=$resv->people_number?>)</TD></TR>
  <TR>
    <TH>국내연락처<BR>(비상연락)</TH>
    <TD><?=$mission->managercontact?></TD>
    <TH>입주희망시간</TH>
    <TD><?=$resv->arrival_time?></TD></TR>
  <TR>
    <TH>방문목적</TH>
    <TD colspan="3"><INPUT type="checkbox"> 병원 <INPUT type="checkbox"> 단체행사 <INPUT type="checkbox"> 안식년 <INPUT type="checkbox"> 자녀교육 <INPUT type="checkbox"> 기타 </TD></TR>
  <TR>
    <TH>입주 예정자 <BR>(메모)</TH>
    <TD colspan="3"><?=str_replace("\r\n", '<br>', $resv->memo)?></TD></TR></TBODY></TABLE>

<DIV class="aCenter mt30 fs2" style="margin:20px">
	<STRONG>신청일: <?=date('Y', $resv->regDate)?>년&nbsp;&nbsp;&nbsp;<?=intval(date('m', $resv->regDate))?>월&nbsp;&nbsp;&nbsp;<?=intval(date('d', $resv->regDate))?>일</STRONG>
</DIV>
	
</BODY></HTML>
