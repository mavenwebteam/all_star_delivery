@extends('layouts.master')
@section('title')Frequently Asked Questions Related to RTA Services | SAG RTA @stop
@section('description')Know all your important queries’ answer regarding RTA services. Mostly, we covered dematerialisation, transmission of shares, nomination, transfer of shares and unpaid/unclaimed dividend and IEPF. @stop
@section('keywords')Transmission of Shares, Nomination, Transfer of shares, Unpaid/unclaimed dividend and IEPF @stop
@section('content')   
 <section class="service-slider-sec sliderservices4">
  <div class="container">
    <div class="row">
      <div class="col-md-6 banner-text">
        <h1 class="page-title">Frequently Ask Questions</h1>
      </div>
      <div class="col-md-6 text-right page-breadcrumb">
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/')}}">Home</a></li>
          <li class="active"><span>FAQ</span></li>
        </ul>
      </div>
    </div>
  </div>
</section>
<section class="main-page-sec">
  <div class="container">
  <div class="row">
    <div class="col-md-12 title-heading">
      <h2>Definition of <span>'Registrar And Transfer Agents'</span></h2>
      <span class="title-border-light"><i class="fa fa-area-chart"></i></span> </div>
      <p class="text-center faq-title-h">Registrar & transfer agents are the trusts or institutions that register<br /> and maintain detailed records of the transactions of investors.</p>
  </div>
  <div class="row">
  <div class="col-md-12">
  <div class="panel-group accordion" id="another" role="tablist" aria-multiselectable="true">
        <h2>Dematerialisation</h2>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i1">
              <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i1" aria-expanded="false"> What is dematerialisation? <span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i1">
              <div class="panel-body">
                <p>The process through which the physical certificates of an investor are converted to an equivalent number of securities in an electronic form and are credited into the BO’s (beneficial owner) account held with his DP(Depository Participant) is dematerialisation.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i2">
              <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i2" aria-expanded="false">How can a physical holding get converted into an electronic holding, or how are securities dematerialised? <span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i2">
              <div class="panel-body">
                <p>For obtaining the dematerialised physical securities, one has to fill in a DRF, the Demat Request Form, which is available with the DP (Depository participant). Then, the filled form along with the physical certificates which are to be dematerialised needs to be submitted. For each ISIN, separate DRF has to be filled.</p>
<p><u>All the required steps for the process of dematerialisation are outlined below:</u></p>                
            <ul class="ul-list">
                  <li>The physical certificates should be surrendered to DP.</li>
 <li>DP informs the Depository regarding the request made through the system</li>
 <li>DP itself submits the certificates to the registrar of the Issuer Company</li>
 <li>The Registrar confirms the dematerialisation request received from the depository.</li>
                </ul>     
<p>After dematerialising the certificates, the Registrar updates the account and informs the depository regarding completion of dematerialisation.</p>                
  <ul class="ul-list">
 <li>The Depository updates his account and informs the DP.</li>
 <li>DP updates the demat account of the investor.</li>
 </ul>               
                
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i3">
              <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i3" aria-expanded="false">What is an ISIN? <span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i3">
              <div class="panel-body">
                <p>ISIN (International Securities Identification Number) is a unique 12 digit alpha-numeric identification number (E.g. - INE383C01018) which is allotted to a security. Equity-fully paid up, equity-partly paid up, equity with differential voting /dividend rights issued by the same issuer will have different ISINs.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i5">
              <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i5" aria-expanded="false">Can the odd lot shares be dematerialised?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i5">
              <div class="panel-body">
                <p>Yes, odd lot share certificates can also be dematerialised.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i6">
              <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i6" aria-expanded="false">Can an electronic holding be converted back to physical certificates?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i6">
              <div class="panel-body">
                <p>Yes, through the process of ‘Rematerialisation’ an electronic holding can be converted into physical certificates. </p>
                <p>If one wishes to get back his securities in the physical form, he needs to fill the RRF (Remat Request Form) and request his DP for rematerialisation of the balances in his securities account. The process of rematerialisation is outlined below:</p>
               <ul class="ul-list">
<li>Make a request for rematerialisation through RRF.</li>
<li>Depository participant informs the depository regarding the request through the system.</li>
<li>the Depository confirms rematerialisation request to the registrar</li>
<li>the Registrar updates accounts and prints certificates</li>
<li>the Depository updates accounts and downloads details to depository participant</li>
<li>the Registrar dispatches physical certificates to the investor</li>
                </ul>
              </div>
            </div>
          </div>
         
          <h2>Transmission of Shares</h2>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i7">
              <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i7" aria-expanded="false">What is transmission?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i7" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i7">
              <div class="panel-body">
                <p>Transmission is the process through which securities of a deceased account holder is transferred to the account of the surviving joint holder (s) / nominee / legal heirs of the deceased account holder. The process of transmission in the case of dematerialized holdings can be completed by submitting documents to the DP, whereas in case of physical securities the surviving joint holder (s) / nominee / legal heirs has to communicate independently with each company/its RTA in which shares are held.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i8">
              <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i8" aria-expanded="false">In the case of joint holdings, if one of the shareholders dies then, is there any provision through which the surviving shareholders gets the shares in their name?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i8" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i8">
              <div class="panel-body">
                <p>In such a case, the surviving shareholders will have to submit a request letter supported by an attested copy of the Death Certificate of the deceased shareholder along with all the relevant share certificates to the company’s registrar and share transfer agent. After receiving the documents, the RTA will delete the name of the deceased shareholder from his records and will return the share certificates to the applicant/registered holder with necessary endorsement.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i9">
              <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i9" aria-expanded="false">If a shareholder who held shares in his sole name dies without leaving a will, can his legal heir/s (either husband/wife/son/daughter, etc.) get the shares transmitted in their names. If yes, How?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i9" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i9">
              <div class="panel-body">
                <p>In a situation like this, in order to get the shares transferred to their names, the legal heirs should obtain a <strong>Succession Certificate</strong> or Letter of Administration with respect to the shares. A true copy of this certificate/letter, duly attested by the Court Officer, or Notary should be sent to the company along with a request letter, transmission form, and all the share certificates in original, to get the transmission completed in their favour.
</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i10">
<div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i10" aria-expanded="false">If the deceased family member who held shares in his/her own name (single) had left a will, how do the legal heir/s get the shares transmitted in their names?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i10" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i10">
              <div class="panel-body">
                <p>The provision under such a situation is that the legal heirs will have to get the will probated by the High Court or the District Court of competent jurisdiction. Then a copy of the probated copy of the will would be sent along with a relevant schedule/annexure reflecting the details of the shares, the relevant share certificates in original and transmission form for transmission to the registrar and share transfer agent.
</p>
              </div>
            </div>
          </div>
       
            <h2>Nomination</h2>
          <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="accordion-i11">
<div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i11" aria-expanded="false">What is meant by nomination of shares? <span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i11" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i11">
              <div class="panel-body">
                <p>Nomination is a process of nominating a person to whom the shares would go/get transferred in the event of death of the shareholder.</p>
  <p>According to the provisions of Companies Act, 2013, regardless of all the other laws, if a shareholder dies (or in case of joint holdings, on the death of all the joint holders), the nominee would be the one who is entitled to the rights on such shares which were held by the deceased. </p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i12">
<div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i12" aria-expanded="false">Who can be a Nominee?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i12" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i12">
              <div class="panel-body">
                <p>A person holding securities of a company may nominate any person as his nominee by filing Form SH13 in the name of whom, all his securities shall be transferred in the event of shareholder’s death.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i13">
 <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i13" aria-expanded="false">Can a Minor be appointed as a Nominee?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i13" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i13">
              <div class="panel-body">
                <p>Yes, a minor can be appointed as a nominee. The procedure is that, the minor’s guardian will sign the nomination form on his behalf.  Along with the name and photograph of the nominee i.e. minor, the name, address and the photograph of the guardian is also required to be submitted with the form.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i14">
<div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i14" aria-expanded="false">How can one make a nomination with regards to the shareholding?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i14" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i14">
              <div class="panel-body">
                <p>A Nomination Form (Form SH 13) in duplicate is required to be submitted by the shareholders which should be duly filled and signed by all the shareholders as per the prescribed format. Only one nominee can be nominated per folio. As soon as a request for the registration of nomination is received by the share transfer agent, it registers the same by allotting a registration number. The duplicate copy of the nomination form indicating the registration number and the date of registration of nomination will be returned to the shareholder(s). For the nomination of shares held in de-mat form, the Depository Participant should be contacted.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i15">
<div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i15" aria-expanded="false">How do the Nominations by Joint Account Holders work?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i15" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i15">
              <div class="panel-body">
                <p>In the case where the securities are held by more than one shareholder jointly, all the joint shareholders together shall nominate any person through the form SH13.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i16">
<div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i16" aria-expanded="false">What is the Procedure to make a fresh nomination?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i16" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i16">
              <div class="panel-body">
                <p><strong>There is a provision for nominating a fresh nominee in place of an existing one.</strong> The earlier nomination may be cancelled or varied by nominating any other person in place of the present one. The procedure is such that, the notice for such a change is given to the company by the holder of securities who has made the nomination. This is done by filling Form No. SH.14.</p>
              </div>
            </div>
          </div>
          
         
           <h2>Transfer of shares</h2>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i18">
<div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i18" aria-expanded="false">If physical shares have been purchased long back and the owner forgets to get them transferred in his favour, what would be the procedure that should be followed now to get it transferred?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i18" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i18">
              <div class="panel-body">
                <p>The transfer deed is valid for a period of one year from the presentation date or the closure date of Register of Members immediately after the presentation date, whichever is later. The presentation date could be obtained from stamp affixed by the Registrar of Companies on the upper portion of the deed. First the owner needs to check whether the transfer deed is still valid. If it is so, then a duly executed and stamped transfer deed along with share certificates should be sent to the company’s registrar and share transfer agent to get the transfer executed in owner’s favour</p>

<p>But, if the validity period of the transfer deed has expired, then in such a case the Registrar of Companies should be approached for the extension of validity of the transfer deeds. Also there is an alternative option of approaching the registered holder/seller, whose signatures are reflecting on the transfer deed as seller, in order to get fresh transfer deeds executed. </p>

<p>When a fresh/revalidated Transfer deed is executed, then it should be submitted to the R&T Agent for transfer. The entities which does not fall under the purview of Registrar of companies, the Revalidation of transfer deed(s) is not applicable to them.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i19">
 <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i19" aria-expanded="false">Can the name of another joint-holder be added to the existing shareholding. What is the procedure that is required to be followed?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i19" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i19">
              <div class="panel-body">
                <p>A transfer deed duly stamped, is required to be executed and submitted for transfer to the R & T agent. Point to be noted that such an addition of name amounts to a change in ownership of shares and the procedure for transfer is required to be followed.</p>
              </div>
            </div>
          </div>
        
           <h2>Unpaid/unclaimed dividend and IEPF</h2>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i20">
<div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i20" aria-expanded="false">In a case where the amount of unpaid or unclaimed dividend is being transferred to IEPF, whether there is also a requirement of transferring the underlying shares of unpaid or unclaimed dividends?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i20" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i20">
              <div class="panel-body">
                <p>Each and every company is required to get their shares transferred, which are underlying since the dividend has remained unpaid or unclaimed against them for a consecutive period of seven years. Thus, such underlying shares of unpaid or unclaimed dividend are required to be transferred to IEPF apart from the amount of unpaid or unclaimed dividend.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i21">
  <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i21" aria-expanded="false">What is the time duration within which the shares are to be transferred to the IEPF?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i21" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i21">
              <div class="panel-body">
                <p>The Rule which follows in such a scenario is that, the shares shall be credited to an IEPF suspense account (on the name of the company) with one of the depository participants identified by the IEPF Authority within thirty days of the shares becoming due to be transferred to the IEPF.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i22">
 <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i22" aria-expanded="false">When should the unclaimed/ unpaid amount be transferred to the IEPF Fund?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i22" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i22">
              <div class="panel-body">
                <p>If there is any amount lying in the Unpaid Dividend Account for 7 years, it should be transferred to IEPF along with interest accrued </p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i23">
    <div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i23" aria-expanded="false">Such shares in respect of which dividend remains unpaid or unclaimed for 7 years has to be transferred to Investor Education and Protection Fund (IEPF). So, can a shareholder claim back the shares from IEPF?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i23" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i23">
              <div class="panel-body">
                <p>Any rightful claimant can claim the transfer of shares from IEPF Authority by applying to the IEPF Authority through e-form IEPF 5 along with the applicable fee.</p>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="accordion-i24">
<div class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#another" href="#accordion-pane-i24" aria-expanded="false">What are the situations under which IEPF 5 can be filed?<span class="plus-minus"><span></span></span> </a> </div>
            </div>
            <div id="accordion-pane-i24" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-i24">
              <div class="panel-body">
                <p>Form IEPF 5 can be filed in cases where shares, unclaimed dividend, matured deposits, matured debentures, application money due for refund or interest thereon, sale proceeds of fractional shares, redemption proceeds of preference shares etc. of any person has been transferred to the IEPF Fund. Such a person may claim the shares or apply for a refund to the Authority by making an application through Form IEPF 5 along with fee under his own signature.</p>
              </div>
            </div>
          </div>
        </div>
  </div>      
 </div>
</div>
</section>
@stop

      

