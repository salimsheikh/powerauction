<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $auction_rules = "<p>Big Deals 24x7 is the online store that was launched to provide the customers with completely stress free shopping experience. We strive to provide customers with best quality, value and convenience in shopping. As the company names suggests, we are ready to offer you big deals at the best price, all time.</p>
<p>The main motto of the company is to provide it's valuable customers with best branded products at the best price at the convenience of their home. Our product ranges are all forms of apparels, electronic products, kitchen appliance to beauty products and many more.</p>
<p>Big Deals 24/7 offer its customers with the following:</p>
<p>Unique products at an EXCEPTIONAL VALUE</p>
<p>Ultimate convenience with products delivered RIGHT TO YOUR DOOR</p>
<p>FREE SHIPPING on the products purchased*</p>
<p>BEST VALUE for your money</p>
<p><br>So come let&rsquo;s start the new trend of shopping and be a Shopping Maniac!!!</p>";

    $terms_condition = '<p>To avail the services offered at www.bigdeals24x7.com you must agree to the following terms and conditions. If you visit, shop or browse at www.bigdeals24x7.com you accept these conditions.</p>
<p>Please read the terms and conditions carefully. While using any current or future services offered by Bigdeals24x7.com, whether or not included in the Bigdeals24x7.com website, you will abide by these Terms &amp; conditions the guidelines and conditions applicable to such service or business.</p>
<p>Privacy Policy</p>
<p>Please review our Privacy Policy, which also governs your visit to www.bigdeals24x7.com, to fully understand our practices.</p>
<p>Terms and conditions are binding for all purchases</p>
<p>All orders are deemed offers for you to purchase our products. We may accept your offer by issuing a confirmatory e-mail and/or mobile confirmation of the products specified in your order. Our acceptance of each such offer is expressly subject to and conditioned on your assent to these terms and conditions of sale. No other terms and conditions will apply.</p>
<p>Electronic Communication</p>
<p>When you visit www.bigdeals24x7.com or send e-mails to us, you are communicating with us electronically. By communicating with us, you consent to receive communication from us electronically. We will communicate with you by e-mail. You agree that all agreements, notices, disclosures, and other communications that we provide to you electronically satisfy any legal requirement that such communication be in writing.</p>
<p>Prices</p>
<p>All prices posted on this website are subject to change without notice. Bigdeals24x7 provides price protection for items during refund and returns. However, if the price of a product increases before the shipment, you shall be notified of the same. In such a scenario, the product shall be shipped based on your confirmation. Posted prices include all taxes and charges. In case there are any additional charges or taxes the same will be mentioned on the website.</p>
<p>Payment</p>
<p>We accept payment by cheque/ net-banking, credit card, debit card, cash card, and mobile payments. EMI facility is also available with us.</p>
<p>Shipping and Handling</p>
<p>We will arrange for shipment of the products to you. Shipping schedules are estimates only and cannot be guaranteed. We are not liable for any delays in the shipments. Title and risk of loss and damages pass on to you upon the products delivery to you. In case reverse shipment cannot be arranged by us due to unavailability of our logistics partners, then in that case you may be requested to send such products through any available courier services. We usually ship the ordered product in 5 to 6 working days.&nbsp;</p>
<p>Return of Products by you</p>
<p>We will accept the return of the products, provided such return is for products that are defective, wrongly delivered, wrong product, damaged during transit, incomplete package etc. subject to the condition that we are informed about such discrepancies within 48 hours from the date of receipt of the product and provided that the products are returned in their original condition. However, in case of transit damages, the issue has to be reported within 48 hours, after which we may not be able to accept the complaint.</p>
<p>Easy replacement</p>
<p>Bigdeals24x7 offers easy replacement for all products sold on Bigdeals24x7.com, under certain conditions which are mentioned below.</p>
<ul>
    <li>Customers will notify us of any damage or defect within 48 hours from the date of receipt of delivery of the products.</li>
<li>In case of transit damages, the issue has to be reported within 48 hours, after which we may not be able to accept the complaint.</li>
<li>We may ask you to share the images of the product and the internal &amp; external packaging material.</li>
<li>Once we agree to replace, the defective/damaged product will be replaced with a brand new product at no extra cost.</li>
<li>In case customer fails to inform bigdeals24x7 within the stipulated time frame, bigdeals24x7 reserves the right to accept or reject such request at its discretion.</li>
<li>Bigdeals24x7 will try to replace the specific product ordered. However, the company reserves the right to offer an alternate product in case the product is Out of Stock or Discontinued by the manufacturer.</li>
<li>The replacement guarantee is valid only in cases of manufacturing defects and transport damages.</li>
</ul>
<p>Return policy is not valid for perishable products such as Cakes, Flowers, Chocolates, etc. If one comes across any issue with these products, they are advised to contact our customer support team within 24 hours. We will try to resolve the complaint in the best possible manner; however, it&rsquo;s not guaranteed that we&rsquo;ll be able to provide replacement/refund request.</p>
<p>Damages due to normal wear &amp; tear and negligence on part of the customer or Digital content such as e-books is not returnable at all.</p>
<p>Changes and Cancellation</p>
<p>Any item additions, quantity changes or specification changes made to accepted orders will be modified in the order details. All sales are final, provided, however, item cancellations and quantity reductions may be made before the order is shipped. We may, without liability, cancel any accepted order before shipment if our credit department does not approve your credit or if there are other problems with the payment mode selected by you.</p>
<p>Cancellation of Order by bigdeals24x7</p>
<p>Bigdeals24x7 reserves the right to refuse or cancel any order placed for a product due to any of the below reasons:</p>

<ul>
    <li>Technical issues related to pricing information.</li>
    <li>Non-availability of the product(s).</li>
    <li>Payment problem identified by Fraud Detection Department.</li>
</ul>

<p>This shall be regardless of whether the order has been confirmed and/or payment been received. 100% payment shall be refunded and the User shall be informed of the same.</p>
<p>Note: We may put additional checks and verifications or seek more information before accepting any order. We will contact you if all or a part of your order is cancelled or if additional information is required to accept your order.</p>
<p>Refunds for the cancelled orders</p>
<p>In case of cancellation before shipment, we process the refund within 24-48 business hours after receiving the cancellation request.</p>
<p>In case of returned product we process the refund once the products have been received and verified at our warehouse.</p>
<p>For payments done through credit/debit cards or net banking, the refund will be processed to the same channel from which the payment was made within 24-48 business hours of us receiving the products back. It may take 2-5 additional business days for the amount to reflect in your account.</p>
<p>In addition, we also provide the hassle-free option of refund through bigdeals24x7 vouchers, which can be used during future purchases.</p>
<p>License and Website Access</p>
<p>General: Bigdeals24x7.com grants you a limited license to access and make personal use of this website and not to download (other than page caching) or modify it, or any portion of it, except with express written consent of www.bigdeals24x7.com.</p>
<p>No license for commercial sale: This license does not include any resale or commercial use of this website or its content; any collection and use of any product listing, description, or pricing; copying of account information for the benefit of another merchant; or any use of data mining, or similar data gathering and extraction tools.</p>
<p>No reproduction: This website or any portion of this website may not be reproduced, duplicated, copied, sold, visited, or otherwise exploited for any commercial purpose without express written consent of Bigdeals24x7.com.</p>
<p>No framing: You may not frame or utilize framing technologies to enclose any trademark, logo, or other proprietary information (including images, text, page layout, or form) of Bigdeals24x7.com without the express written consent.</p>
<p>Your Account</p>
<p>Protection of Your Account: You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer. You shall be responsible for all activities that occur under your account or password.</p>
<p>Use by Children: Bigdeals24x7.com does sell products for children, but it sells them to adults. If you are under age of 18 years, you may use Bigdeals24x7.com only with involvement of a parent or guardian. Bigdeals24x7.com and its affiliates reserve the right to refuse service, terminate accounts, remove or edit content, or cancel orders in their sole discretion.</p>
<p>Gift Voucher/Wallet/Prepaid Credit (the "Credit")</p>
<ul>
<li>The Credit cannot be exchanged for cash or cheque.</li>
<li>The holder of the Credit is deemed to be the beneficiary.</li>
<li>The full amount of Credit can be applied to only one account on Bigdeals24x7.com.</li>
<li>Credit cannot be used with other promotional offers on Bigdeals24x7.com.</li>
<li>The Credit can be redeemed on bigdeals24x7.com</li>
<li>Gift Voucher hard copies shall be mailed to the Customer.</li>
<li>Gift Voucher is valid for 12 months from the date of purchase. On request from Customer, Gift Vouchers can be emailed either to the registered email ID of Customer or any other email ID designated by Customer, once the Order has been processed.</li>
</ul>
<p>Reviews, Comments, Communications, and other content</p>
<p>Nature of content: Visitors to Bigdeals24x7.com may post reviews, comments and other content; send e-cards and other communications; and submit suggestions, ideas, comments, questions or other information, as long as the content is not illegal, obscene, threatening, defamatory, invasive of privacy, infringing of intellectual property rights to otherwise injurious to third party or objectionable and does not consist of or contains software virus, political campaigning, commercial solicitation, mass mailing or any form of spam.</p>
<p>False information: You may not use false email address, impersonate any person or entity, or otherwise mislead as to the origin of a card or other content. Bigdeals24x7 reserves the right (but not the obligation) to remove or edit such content but does not regularly review posted contents.</p>
<p>Risk of Loss</p>
<p>All items purchased from Bigdeals24x7.com are made pursuant to the shipment contract. This means that the risk of loss and title for such item passes on to you upon the products delivery.</p>
<p>Product Description</p>
<p>Bigdeals24x7.com attempt to be as accurate as possible. However, Bigdeals24x7.com makes no warranties that the product description and any other content of its site are accurate, complete, reliable, and current or error free. The product offered by Bigdeals24x7.com itself is not as described and its sole remedy is to return in its unused condition.</p>
<p>Site Policies, Modification, and Severability</p>
<p>Please review our other policies. We reserve the right to make changes to our website, policies, and these Terms and Conditions at any time. If any of these conditions shall be deemed invalid, void, or for any reason unenforceable, that condition shall be deemed severable and will not affect the validity and enforceability of any remaining conditions.</p>
<p>Intellectual Property Rights</p>
<p>Copyright Protection: All content included on this site, such as text, graphics, logos, button icons, and audio clips, digital downloads data compilations and software, is the property of Bigdeals24x7.com and protected by the Indian Copyright law. All software used in this site is the property of Bigdeals24x7.com and is protected under the Indian Copyright law.</p>
<p>Trademarks:</p>
<ul>
<li>Protected Marks: bigdeals24x7, www.bigdeals24x7.com, website is registered trademarks of Bigdeals24x7.com.</li>
<li>Protected Graphics: All Bigdeals24x7.com graphics, logos, page headers, button icons, scripts and service names are trademarks or trade dress of Bigdeals24x7.com.</li>
</ul>
<p>Governing Law and Jurisdiction</p>
<p>These terms and conditions will be construed only in accordance with the laws of India. In respect of all matters/disputes arising out of, in connection with or in relation to these terms and conditions or any other conditions on this website, only the competent Courts at Mumbai, Maharashtra shall have jurisdiction, to the exclusion of all other courts.</p>
<p>Disclaimer of warranties and Limitation of Liability</p>
<p>THIS SITE IS PROVIDED BY BIGDEALS24X7 ON AN "AS IS" AND "AS AVAILABLE" BASIS. BIGDEALS24X7.COM MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED, AS TO THE OPERATION OF THIS SITE OR THE INFORMATION, CONTENT, MATERIALS, OR PRODUCTS INCLUED ON THIS SITE. YOU EXPRESSLY AGREE THAT YOUR USE OF THIS SITE IS AT YOUR SOLE RISK.</p>
<p>TO THE FULL EXTENT PERMISSIBLE BY APPLICABLE LAW, BIGDEALS24X7.COM DISCLAIMS ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. BIGDEALS24X7.COM DOES NOT WARRANT THAT THE SITE, ITS SERVERS, OR EMAIL SENT FROM BIGDEALS24X7.COM ARE FREE OF VIRUS OR OTHER HARMFUL COMPONENTS. BIGDEALS24X7.COM WILL NOT BE LIABLE FOR ANY DAMAGES OF ANY KIND ARISING FROM THE USE OF THIS SITE, INCLUDING, BUT NOT LIMITED TO DIRECT, INDIRECT, INCIDENTAL, PUNITIVE AND CONSEQUENTIAL DAMAGES.</p>';

        $privacy_policy = '<p>We insist on the highest standards for secure transactions and customer information privacy since we value the trust you place in us.</p>
<ul>
<li>Our privacy policy is subject to change at any time without prior notice. To make sure you are aware of any changes, please review this policy periodically.</li>
<li>By visiting our website you agree to be bound by the terms and conditions of this Privacy Policy. If you do not agree please do not use or access our Website.</li>
<li>By mere use of the Website, you expressly consent to our use and disclosure of your personal information in accordance with this Privacy Policy. This Privacy Policy is incorporated into and subject to the Terms of Use.&nbsp;</li>
</ul>
<p>Use and Sharing of Information</p>
<p>At no time will we sell your personally-identifiable data without your permission unless set forth in this Privacy Policy. The information we receive about you or from you may be used by us or shared by us with our corporate affiliates, dealers, agents, vendors and other third parties to help process your request; to comply with any law, regulation, audit or court order; to help improve our website or the products or services we offer; for research; to better understand our customers\' needs; to develop new offerings; and to alert you to new products and services (of us or our business associates) in which you may be interested. We may also combine information you provide us with information about you that is available to us internally or from other sources in order to better serve you.</p>
<p>We do not share, sell, trade or rent your personal information to third parties for unknown reasons.</p>
<p>Cookies</p>
<p>"Cookies" are small identifiers sent from a web server and stored on your computer\'s hard drive, that help us to recognize you if you visit our website again.</p>
<p>From time to time, we may place "cookies" on your personal computer. Also, our site uses cookies to track how you found our site. To protect your privacy we do not use cookies to store or transmit any personal information about you on the Internet. You have the ability to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. If you choose to decline cookies certain features of the site may not function properly or at all as a result.</p>
<p>Links</p>
<p>Our website contains links to other sites. Such other sites may use information about your visit to this website. Our Privacy Policy does not apply to practices of such sites that we do not own or control or to people we do not employ. Therefore, we are not responsible for the privacy practices or the accuracy or the integrity of the content included on such other sites. We encourage you to read the individual privacy statements of such websites.</p>
<p>Security</p>
<p>We safeguard your privacy using known security standards and procedures and comply with applicable privacy laws. Our websites combine industry-approved physical, electronic and procedural safeguards to ensure that your information is well protected throughout its life cycle in our infrastructure.</p>
<p>Sensitive data is hashed or encrypted when it is stored in our infrastructure. Sensitive data is decrypted, processed and immediately re-encrypted or discarded when no longer necessary. We host web services in audited data centres, with restricted access to the data processing servers. Controlled access, recorded and live-monitored video feeds, 24/7 staffed security and biometrics provided in such data centres ensure that we provide secure hosting.</p>
<p>Changes to this Privacy Policy</p>
<p>Our privacy policy is subject to change at any time without notice. We may change our Privacy Policy from time to time. Please review this policy periodically to make sure you are aware of any changes.</p>';

        DB::table('settings')->insert([
            [            
                'option_name' => 'terms_condition',
                'option_value' => $terms_condition,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [            
                'option_name' => 'privacy_policy',
                'option_value' => $privacy_policy,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [            
                'option_name' => 'auction_rules',
                'option_value' => $auction_rules,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [            
                'option_name' => 'auction_expire_minutes',
                'option_value' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [            
                'option_name' => 'list_per_page',
                'option_value' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],        
        ]);
    }
}
