<?php
// Merchant key here as provided by Payu
$MERCHANT_KEY = "ATRfMfRj";

// Merchant Salt as provided by Payu
$SALT = "v4DMJFb1K7";


// End point - change to https://secure.payu.in for LIVE mode
$PAYU_BASE_URL = "https://secure.payu.in";

$action = '';

$posted = array();
if(!empty($_POST)) {
    //print_r($_POST);
  foreach($_POST as $key => $value) {    
    $posted[$key] = $value; 
	
  }
}

$formError = 0;

if(empty($posted['txnid'])) {
  // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
		  || empty($posted['service_provider'])
  ) {
    $formError = 1;
  } else {
    //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
	$hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';	
	foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

    $hash_string .= $SALT;


    $hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}
?>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Plan Online Trip Payments Page</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
        <link href="style.css" rel="stylesheet">
        <script src="js/jquery-1.11.3.js"></script>
        <script src="js/modernizr-2.0.6.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900,100italic,300italic,400italic,500italic,700italic,900italic' rel='stylesheet' type='text/css'>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
	  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
    </head>
  
  
  <body onload="submitPayuForm()">
      <header>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="logo-header">
                            <a href="http://www.planonlinetrip.com/"><img src="images/logo-header.png" alt="Logo Header"></a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="contact-header">
                            <div class="contact-number">24x7 Call Assistant<span>+91-9953681872</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    <?php if($formError) { ?>
		<span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>
	
	 <section class="easy-secure">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-5">
                        <div class="easy-secure-left">
						<span>Credit Card Transaction's suite of services!</span>
							<form action="<?php echo $action; ?>" method="post" name="payuForm">
							  <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
							  <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
							  <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
								
								<div class="row form-group">
                                    <div class="col-xs-12 col-sm-6 col-md-6 padding-right form-group">
                                        <label class="control-label">Amount<i>*</i></label>
                                        <input class="form-control" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" data-error="Please Enter Your Amount !!" required placeholder="INR" aria-describedby="basic-addon2"/>
										<div class="help-block with-errors"></div>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 padding-right form-group">
                                        <label class="control-label">Name<i>*</i></label>
                                        <input class="form-control" name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" /><div class="help-block with-errors"></div>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 padding-right form-group">
                                        <label class="control-label">Email<i>*</i></label>
                                        <input class="form-control" name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" />
										<div class="help-block with-errors"></div>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 padding-right form-group">
                                        <label class="control-label">Phone<i>*</i></label>
                                        <input class="form-control" name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" />
										<div class="help-block with-errors"></div>
									</div>
								<input type="hidden" name="productinfo" value="Plan Online Trip Pvt. Ltd." size="64" />
								<input type="hidden" name="surl" value="http://slantwebtech.com/" size="64" />
								<input type="hidden" name="furl" value="http://www.planonlinetrip.com/" size="64" />
								<input type="hidden" name="service_provider" value="payu_paisa" size="64" />
								
								 <div class="col-xs-12 col-sm-12 col-md-12 form-group">
                                        <label class="control-label">Address<i>*</i></label>
                                        <input class="form-control" type="text" name="billing_cust_address" id="billing_cust_address" required placeholder="Enter Address" data-error="Please Enter Your Address !!" aria-describedby="basic-addon2">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 padding-right form-group" >
                                        <label class="control-label">Country<i>*</i></label>
                                        <select class="form-control" name="billing_cust_country" id="billing_cust_country" required>
                                            <option value="ABW">Aruba</option>
                                            <option value="AFG">Afghanistan</option>
                                            <option value="AGO">Angola</option>
                                            <option value="AIA">Anguilla</option>
                                            <option value="ALA">Åland Islands</option>
                                            <option value="ALB">Albania</option>
                                            <option value="AND">Andorra</option>
                                            <option value="ANT">Netherlands Antilles</option>
                                            <option value="ARE">United Arab Emirates</option>
                                            <option value="ARG">Argentina</option>
                                            <option value="ARM">Armenia</option>
                                            <option value="ASM">American Samoa</option>
                                            <option value="ATA">Antarctica</option>
                                            <option value="ATF">French Southern Territories</option>
                                            <option value="ATG">Antigua and Barbuda</option>
                                            <option value="AUS">Australia</option>
                                            <option value="AUT">Austria</option>
                                            <option value="AZE">Azerbaijan</option>
                                            <option value="BDI">Burundi</option>
                                            <option value="BEL">Belgium</option>
                                            <option value="BEN">Benin</option>
                                            <option value="BFA">Burkina Faso</option>
                                            <option value="BGD">Bangladesh</option>
                                            <option value="BGR">Bulgaria</option>
                                            <option value="BHR">Bahrain</option>
                                            <option value="BHS">Bahamas</option>
                                            <option value="BIH">Bosnia and Herzegovina</option>
                                            <option value="BLM">Saint Barthélemy</option>
                                            <option value="BLR">Belarus</option>
                                            <option value="BLZ">Belize</option>
                                            <option value="BMU">Bermuda</option>
                                            <option value="BOL">Bolivia</option>
                                            <option value="BRA">Brazil</option>
                                            <option value="BRB">Barbados</option>
                                            <option value="BRN">Brunei Darussalam</option>
                                            <option value="BTN">Bhutan</option>
                                            <option value="BVT">Bouvet Island</option>
                                            <option value="BWA">Botswana</option>
                                            <option value="CAF">Central African Republic</option>
                                            <option value="CAN">Canada</option>
                                            <option value="CCK">Cocos (Keeling) Islands</option>
                                            <option value="CHE">Switzerland</option>
                                            <option value="CHL">Chile</option>
                                            <option value="CHN">China</option>
                                            <option value="CIV">Côte d`Ivoire</option>
                                            <option value="CMR">Cameroon</option>
                                            <option value="COD">Congo, the Democratic Republic of the</option>
                                            <option value="COG">Congo</option>
                                            <option value="COK">Cook Islands</option>
                                            <option value="COL">Colombia</option>
                                            <option value="COM">Comoros</option>
                                            <option value="CPV">Cape Verde</option>
                                            <option value="CRI">Costa Rica</option>
                                            <option value="CUB">Cuba</option>
                                            <option value="CXR">Christmas Island</option>
                                            <option value="CYM">Cayman Islands</option>
                                            <option value="CYP">Cyprus</option>
                                            <option value="CZE">Czech Republic</option>
                                            <option value="DEU">Germany</option>
                                            <option value="DJI">Djibouti</option>
                                            <option value="DMA">Dominica</option>
                                            <option value="DNK">Denmark</option>
                                            <option value="DOM">Dominican Republic</option>
                                            <option value="DZA">Algeria</option>
                                            <option value="ECU">Ecuador</option>
                                            <option value="EGY">Egypt</option>
                                            <option value="ERI">Eritrea</option>
                                            <option value="ESH">Western Sahara</option>
                                            <option value="ESP">Spain</option>
                                            <option value="EST">Estonia</option>
                                            <option value="ETH">Ethiopia</option>
                                            <option value="FIN">Finland</option>
                                            <option value="FJI">Fiji</option>
                                            <option value="FLK">Falkland Islands (Malvinas)</option>
                                            <option value="FRA">France</option>
                                            <option value="FRO">Faroe Islands</option>
                                            <option value="FSM">Micronesia, Federated States of</option>
                                            <option value="GAB">Gabon</option>
                                            <option value="GBR">United Kingdom</option>
                                            <option value="GEO">Georgia</option>
                                            <option value="GGY">Guernsey</option>
                                            <option value="GHA">Ghana</option>
                                            <option value="GIN">N Guinea</option>
                                            <option value="GIB">Gibraltar</option>
                                            <option value="GLP">Guadeloupe</option>
                                            <option value="GMB">Gambia</option>
                                            <option value="GNB">Guinea-Bissau</option>
                                            <option value="GNQ">Equatorial Guinea</option>
                                            <option value="GRC">Greece</option>
                                            <option value="GRD">Grenada</option>
                                            <option value="GRL">Greenland</option>
                                            <option value="GTM">Guatemala</option>
                                            <option value="GUF">French Guiana</option>
                                            <option value="GUM">Guam</option>
                                            <option value="GUY">Guyana</option>
                                            <option value="HKG">Hong Kong</option>
                                            <option value="HMD">Heard Island and McDonald Islands</option>
                                            <option value="HND">Honduras</option>
                                            <option value="HRV">Croatia</option>
                                            <option value="HTI">Haiti</option>
                                            <option value="HUN">Hungary</option>
                                            <option value="IDN">Indonesia</option>
                                            <option value="IMN">Isle of Man</option>
                                            <option value="IND" selected="selected">India</option>
                                            <option value="IOT">British Indian Ocean Territory</option>
                                            <option value="IRL">Ireland</option>
                                            <option value="IRN">Iran, Islamic Republic of</option>
                                            <option value="IRQ">Iraq</option>
                                            <option value="ISL">Iceland</option>
                                            <option value="ISR">Israel</option>
                                            <option value="ITA">Italy</option>
                                            <option value="JAM">Jamaica</option>
                                            <option value="JEY">Jersey</option>
                                            <option value="JOR">Jordan</option>
                                            <option value="JPN">Japan</option>
                                            <option value="KAZ">Kazakhstan</option>
                                            <option value="KEN">Kenya</option>
                                            <option value="KGZ">Kyrgyzstan</option>
                                            <option value="KHM">Cambodia</option>
                                            <option value="KIR">Kiribati</option>
                                            <option value="KNA">Saint Kitts and Nevis</option>
                                            <option value="KOR">Korea, Republic of</option>
                                            <option value="KWT">Kuwait</option>
                                            <option value="LAO">Lao People`s Democratic Republic</option>
                                            <option value="LBN">Lebanon</option>
                                            <option value="LBR">Liberia</option>
                                            <option value="LBY">Libyan Arab Jamahiriya</option>
                                            <option value="LCA">Saint Lucia</option>
                                            <option value="LIE">Liechtenstein</option>
                                            <option value="LKA">Sri Lanka</option>
                                            <option value="LSO">Lesotho</option>
                                            <option value="LTU">Lithuania</option>
                                            <option value="LUX">Luxembourg</option>
                                            <option value="LVA">Latvia</option>
                                            <option value="MAC">Macao</option>
                                            <option value="MAF">Saint Martin (French part)</option>
                                            <option value="MAR">Morocco</option>
                                            <option value="MCO">Monaco</option>
                                            <option value="MDA">Moldova</option>
                                            <option value="MDG">Madagascar</option>
                                            <option value="MDV">Maldives</option>
                                            <option value="MEX">Mexico</option>
                                            <option value="MHL">Marshall Islands</option>
                                            <option value="MKD">Macedonia, the former Yugoslav Republic of</option>
                                            <option value="MLI">Mali</option>
                                            <option value="MLT">Malta</option>
                                            <option value="MMR">Myanmar</option>
                                            <option value="MNE">Montenegro</option>
                                            <option value="MNG">Mongolia</option>
                                            <option value="MNP">Northern Mariana Islands</option>
                                            <option value="MOZ">Mozambique</option>
                                            <option value="MRT">Mauritania</option>
                                            <option value="MSR">Montserrat</option>
                                            <option value="MTQ">Martinique</option>
                                            <option value="MUS">Mauritius</option>
                                            <option value="MWI">Malawi</option>
                                            <option value="MYS">Malaysia</option>
                                            <option value="MYT">Mayotte</option>
                                            <option value="NAM">Namibia</option>
                                            <option value="NCL">New Caledonia</option>
                                            <option value="NER">Niger</option>
                                            <option value="NFK">Norfolk Island</option>
                                            <option value="NGA">Nigeria</option>
                                            <option value="NIC">Nicaragua</option>
                                            <option value="NOR">R Norway</option>
                                            <option value="NIU">Niue</option>
                                            <option value="NLD">Netherlands</option>
                                            <option value="NPL">Nepal</option>
                                            <option value="NRU">Nauru</option>
                                            <option value="NZL">New Zealand</option>
                                            <option value="OMN">Oman</option>
                                            <option value="PAK">Pakistan</option>
                                            <option value="PAN">Panama</option>
                                            <option value="PCN">Pitcairn</option>
                                            <option value="PER">Peru</option>
                                            <option value="PHL">Philippines</option>
                                            <option value="PLW">Palau</option>
                                            <option value="PNG">Papua New Guinea</option>
                                            <option value="POL">Poland</option>
                                            <option value="PRI">Puerto Rico</option>
                                            <option value="PRK">Korea, Democratic People`s Republic of</option>
                                            <option value="PRT">Portugal</option>
                                            <option value="PRY">Paraguay</option>
                                            <option value="PSE">Palestinian Territory, Occupied</option>
                                            <option value="PYF">French Polynesia</option>
                                            <option value="QAT">Qatar</option>
                                            <option value="REU">Réunion</option>
                                            <option value="ROU">Romania</option>
                                            <option value="RUS">Russian Federation</option>
                                            <option value="RWA">Rwanda</option>
                                            <option value="SAU">Saudi Arabia</option>
                                            <option value="SDN">Sudan</option>
                                            <option value="SEN">Senegal</option>
                                            <option value="SGP">Singapore</option>
                                            <option value="SGS">South Georgia and the South Sandwich Islands</option>
                                            <option value="SHN">Saint Helena</option>
                                            <option value="SJM">Svalbard and Jan Mayen</option>
                                            <option value="SLB">Solomon Islands</option>
                                            <option value="SLE">Sierra Leone</option>
                                            <option value="SLV">El Salvador</option>
                                            <option value="SMR">San Marino</option>
                                            <option value="SOM">Somalia</option>
                                            <option value="SPM">Saint Pierre and Miquelon</option>
                                            <option value="SRB">Serbia</option>
                                            <option value="STP">Sao Tome and Principe</option>
                                            <option value="SUR">Suriname</option>
                                            <option value="SVK">Slovakia</option>
                                            <option value="SVN">Slovenia</option>
                                            <option value="SWE">Sweden</option>
                                            <option value="SWZ">Swaziland</option>
                                            <option value="SYC">Seychelles</option>
                                            <option value="SYR">Syrian Arab Republic</option>
                                            <option value="TCA">Turks and Caicos Islands</option>
                                            <option value="TCD">Chad</option>
                                            <option value="TGO">Togo</option>
                                            <option value="THA">Thailand</option>
                                            <option value="TJK">Tajikistan</option>
                                            <option value="TKL">Tokelau</option>
                                            <option value="TKM">Turkmenistan</option>
                                            <option value="TLS">Timor-Leste</option>
                                            <option value="TON">Tonga</option>
                                            <option value="TTO">Trinidad and Tobago</option>
                                            <option value="TUN">Tunisia</option>
                                            <option value="TUR">Turkey</option>
                                            <option value="TUV">Tuvalu</option>
                                            <option value="TWN">Taiwan, Province of China</option>
                                            <option value="TZA">Tanzania, United Republic of</option>
                                            <option value="UGA">Uganda</option>
                                            <option value="UKR">Ukraine</option>
                                            <option value="UMI">United States Minor Outlying Islands</option>
                                            <option value="URY">Uruguay</option>
                                            <option value="USA">United States</option>
                                            <option value="UZB">Uzbekistan</option>
                                            <option value="VAT">Holy See (Vatican City State)</option>
                                            <option value="VCT">Saint Vincent and the Grenadines</option>
                                            <option value="VEN">Venezuela</option>
                                            <option value="VGB">Virgin Islands, British</option>
                                            <option value="VIR">Virgin Islands, U.S.</option>
                                            <option value="VNM">Viet Nam</option>
                                            <option value="VUT">Vanuatu</option>
                                            <option value="WLF">Wallis and Futuna</option>
                                            <option value="WSM">Samoa</option>
                                            <option value="YEM">Yemen</option>
                                            <option value="ZAF">South Africa</option>
                                            <option value="ZMB">Zambia</option>
                                            <option value="ZWE">Zimbabwe</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 padding-left form-group">
                                        <label class="control-label">State<i>*</i></label>
                                        <input class="form-control" required type="text" name="billing_cust_state" id="billing_cust_state" placeholder="Enter State" data-error="Please Enter Your State !!" aria-describedby="basic-addon2">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 padding-right form-group">
                                        <label class="control-label">City<i>*</i></label>
                                        <input type="text" required class="form-control" placeholder="Enter City"  name="billing_city" id="billing_city" data-error="Please Enter Your City !!" aria-describedby="basic-addon2">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 padding-left form-group">
                                        <label class="control-label">Zip<i>*</i></label>
                                        <input type="text" required class="form-control" placeholder="Enter Zip" name="billing_zip" id="billing_zip" data-error="Please Enter Your ZipCode !!" aria-describedby="basic-addon2">
                                        <div class="help-block with-errors"></div>
                                    </div>
								<div class="col-xs-12 col-sm-12 col-md-12 align-center">
                                <?php if(!$hash) { ?>
									<input type="submit" value="Submit" />
								  <?php } ?>
								</div>
							  </div>
							</form>
						</div>
					</div>
					<div class="col-xs-12 col-sm-7 col-md-7">
                        <div class="easy-secure-right">
                            <h1>Easy &amp; Secure Tour Payments</h1>
                            <span>Fill the Form with Easy Steps &amp; Get Access to...</span>
                            <ul>
                                <li>Highly Customized Individual, Family, &amp; Group Tours</li>
                                <li>Competitive Prices</li>
                                <li>Outstanding Tours</li>
                            </ul>
                            <ul class="icons-pay">
                                <li><img src="images/icon-visa.png" alt="Visa"></li>
                                <li><img src="images/icon-master.png" alt="Visa"></li>
                                <li><img src="images/icon-american.png" alt="Visa"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	 <section class="strip-red">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="strip-red-inner">
                            <span class="easy">It's Easy</span>
                            <span class="fast">It's Fast</span>
                            <span class="simple">It's Simple</span>
                            <span class="secure">It's Secure</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<b>Plan Online Trip. - Trusted Travel Partner</b>
				</div>
			</div>
		</div>
	</footer>
  </body>
</html>
