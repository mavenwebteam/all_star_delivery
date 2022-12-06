@extends('layouts.master')

@section('title')Download RTA (Registrar & Transfer Agent) Forms in PDF Format | SAG RTA @stop

@section('description')Easy to download RTA (registrar & transfer agent) forms in PDF format according to the name wise. Also, we listed all RTA forms. @stop

@section('keywords')rta forms, list of rta forms, download rta forms, affidavit for change of signature, bankers verification for specimen sign, form iepf 5, request letter for change of address, form no. sh-13 nomination form @stop

@section('content')
<section class="service-slider-sec sliderservices2">
  <div class="container">
    <div class="row">
      <div class="col-md-6 banner-text">
        <h1 class="page-title">RTA Forms</h1>
      </div>
      <div class="col-md-6 text-right page-breadcrumb">
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/')}}">Home</a></li>
          <li class="active"><span>RTA Forms</span></li>
        </ul>
      </div>
    </div>
  </div>
</section>
<section class="main-page-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-12 title-heading">
        <h2>You Can Download <span>RTA Forms Here</span></h2>
        <span class="title-border-light"><i class="fa fa-area-chart"></i></span> </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="rta-form-table table-responsive">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
            <tr>
              <th>No.</th>
              <th>Form Name</th>
              <th>Download</th>
            </tr>
            <tr>
              <td>1.</td>
              <td>AFFIDAVIT FOR CHANGE OF SIGNATURE/NON AVAILABILITY OF SIGNATURE</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Affidavit-for-change-in-signatures.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>2.</td>
              <td>FORMAT OF AFFIDAVIT FOR TRANSMISSION OF SHARES WITHOUT PRODUCING PROBATE / SUCCESSION CERTIFICATE / LETTERS OF ADMINISTRATION</td>
              <td><a href="{{ URL::asset('resources/assets/forms/doc/Affidavit-for-Transmission.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>3.</td>
              <td>BANKERS VERIFICATION FOR SPECIMEN SIGN</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/Bankers Verification for Specimen Sign.pdf') }}" alt="BANKERS VERIFICATION FOR SPECIMEN SIGN" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/pdf-icon.png') }}" alt="pdf-icon"></a></td>
            </tr>
            <tr>
              <td>4.</td>
              <td>Form IEPF 5 <strong>(Form IEPF 5 Help)</strong> <i> Please use Adobe Reader for this form</i></td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Form_IEPF-5.zip') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/zip-icon.png') }}" alt="zip-icon"></a></td>
            </tr>
            <tr>
              <td>5.</td>
              <td>LETTER OF UNDERTAKING FOR ISSUE OF DUPLICATE DIVIDEND WARRANT</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/LUT-for-Duplicate-Dividend-Warrant.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>6.</td>
              <td>BANK MANDATE/PAN AND EMAIL ID REGISTRATION FORM</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/PAN-BANK-MANDATE.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>7.</td>
              <td>REQUEST LETTER FOR CHANGE OF ADDRESS</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Request-Letter-for-Change-of-Address.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>8.</td>
              <td>REQUEST LETTER FOR CHANGE OF NAME</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Request-Letter-For-Change-of-Name.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>9.</td>
              <td>REQUEST LETTER FOR DELETION OF NAME</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Request-Letter-for-Deletion-of-Name.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>10.</td>
              <td>FORM NO. SH-4 SECURITIES TRANSFER FORM</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/SH-4-Securities-Transfer-Form.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>11.</td>
              <td>FORM NO. SH-13 NOMINATION FORM</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/SH-13-Nomination-Form.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>12.</td>
              <td>FORM NO. SH-14 CANCELLATION OR VARIATION OF NOMINATION</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/SH-14-Cancellation-or-Variation-of-Nomination.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>13.</td>
              <td>Application Form for Change in Signature(s)-Bank Attestation</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Application Form for Change in Signature(s)-Bank Attestation.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>14.</td>
              <td>Application Form for Transmission or Transposition or Name Deletion</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Application Form for Transmission or Transposition or Name Deletion.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>15.</td>
              <td>Consolidation Amalgamation of Folios</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Consolidation Amalgamation of Folios.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>16.</td>
              <td>Draft for Public Notice on ACCT of Lost of SH CRTF</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Draft for Public Notice on ACCT of Lost of SH CRTF.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>17.</td>
              <td>Draft for the Indemnity Agreement</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Draft for the Indemnity Agreement.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>18.</td>
              <td>Draft of Affadavit</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Draft of Affadavit.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>19.</td>
              <td>Draft of Indemnity Bond</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Draft of Indemnity Bond.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>20.</td>
              <td>Endorsement of my Changed Name</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Endorsement of my Changed Name.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>21.</td>
              <td>For Issue of Duplicate Share Certificate(s)</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/For Issue of Duplicate Share Certificate(s).docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>22.</td>
              <td>Intimation of PAN GIR No</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Intimation of PAN GIR No.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>23.</td>
              <td>Letter of Indemnity cum Disclaimer</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Letter of Indemnity cum Disclaimer.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>24.</td>
              <td>Query Form</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Query Form.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>25.</td>
              <td>Request for ECS Mandate</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Request for ECS Mandate.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
            <tr>
              <td>26.</td>
              <td>Undertaking for Lost NCD Instrument</td>
              <td><a href="{{ URL::asset('resources/assets/documents/forms/doc/Undertaking for Lost NCD Instrument.docx') }}" rel="nofollow" target="_blank"><img src="{{ URL::asset('resources/assets/images/word-icon.png') }}" alt="word-icon"></a></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@stop 