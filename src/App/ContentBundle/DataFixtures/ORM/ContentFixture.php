<?php

namespace App\UserBundle\DataFixtures\ORM;

use App\ContentBundle\Entity\Content;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ContentFixture implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $welcomeLogin = new Content('welcome_login');
        $welcomeLogin->setTitle("Welcome to Link");
        $welcomeLogin->setBody(
            "<p>
                Link is a portal that acts as a meeting place for our members and students across Australia.
                It allows students to create profiles and upload CVs,
                for our members to view and get in contact with students whom they are interested in.
            </p>
            <hr>
            <h4>Getting started</h4>
            <p>
                Once you’ve registered for an account, log in and start using this portal.
                <br><b>Students:</b><br>
                Step 1 – Create your profile by entering your details.<br>
                Step 2 – Briefly introduce yourself in the About Me section.<br>
                Step 3 – Upload your certifications and CV.<br>
                Step 4 – Submit your profile.<br><b>Members:</b><br>
                Step 1 – Start searching students by name, or through the use of filters.<br>
                Step 2 – Click on the student name to expand his/her profile.<br>
                Step 3 – Click the + button to save the student to Shortlist.<br>
                Step 4 – Export your Shortlist.<br>
                Step 5 – Contact the students at will.
            </p>"
        );
        $manager->persist($welcomeLogin);

        $tc = new Content('terms_conditions');
        $tc->setTitle("Terms & Conditions");
        $tc->setBody(
            '<h4 style="text-align:center">Licence To Use&nbsp;Link</h4>
            <p>Before opening this software application please read carefully the enclosed Licence. By clicking on AGREE, You acknowledge that You have read, understood and agree to be legally bound by the terms and conditions of this Agreement.</p>
            <table class="table table-borderless table-condensed table-hover"> <tbody> <tr> <td>1.</td> <td colspan="2">Licence</td> <td>&nbsp;</td> </tr> <tr> <td>1.1</td> <td colspan="2">GS1 Australia Limited ABN 67 005 529 920 of Unit 100,45 Gilby Road, Mt
            Waverley, Victoria 3149, a company limited by guarantee (&quot;the Licensor&quot;), hereby grants You a non-exclusive licence to use the Link (&quot;the Software&quot;) and the accompanying documentation on the following terms.</td> <td>&nbsp;</td>
            </tr> <tr> <td>1.2</td> <td colspan="2">The copyright and all other rights in the Software and the accompanying documentation remain with the Licensor.</td> <td>&nbsp;</td> </tr> <tr> <td>2.</td> <td colspan="2">Acceptance</td> <td>&nbsp;</td>
            </tr> <tr> <td>&nbsp;</td> <td colspan="2">You are deemed to accept the terms of this Licence by clicking on AGREE. If You do not wish to accept these terms, You should exit from this application immediately.</td> <td>&nbsp;</td> </tr> <tr>
            <td>3.</td> <td colspan="2">Scope of Licence</td> <td>&nbsp;</td> </tr> <tr> <td>3.1</td> <td colspan="2">This Licence permits You to:</td> <td>&nbsp;</td> </tr> <tr> <td>&nbsp;</td> <td>a)</td> <td>use the Software for its intended purpose as
            provided; and</td> </tr> <tr> <td>&nbsp;</td> <td>b)</td> <td>make one copy of the stand-alone Software (if applicable) for back-up purposes only, which must reproduce and include the Licensor&#39;s copyright notice.</td> </tr> <tr> <td>3.2</td>
            <td colspan="2">You shall not:</td> </tr> <tr> <td>&nbsp;</td> <td>a)</td> <td>use or copy the Software other than as permitted by this Licence;</td> </tr> <tr> <td>&nbsp;</td> <td>b)</td> <td>modify, adapt, merge, translate, decompile,
            disassemble, or reverse engineer the Software except where directed to by the law; and</td> </tr> <tr> <td>&nbsp;</td> <td>c)</td> <td>use, sell, assign, rent, sub-license, loan, mortgage, charge or otherwise deal in any way in the Software or its
            accompanying documentation or any interest in them or under this Licence except as expressly provided in this Licence.</td> </tr> <tr> <td>3.3</td> <td colspan="2">You shall:</td> </tr> <tr> <td>&nbsp;</td> <td>a)</td> <td colspan="2">use the
            Software in accordance with any instructions or recommendations provided by the Licensor which accompany the Software;</td> </tr> <tr> <td>&nbsp;</td> <td>b)</td> <td colspan="2">take all reasonable care when using the Software;</td> </tr> <tr>
            <td>&nbsp;</td> <td>c)</td> <td colspan="2">promptly update the stand-alone Software (if applicable) when requested by the Licensor; and</td> </tr> <tr> <td>&nbsp;</td> <td>d)</td> <td colspan="2">notify the Licensor and comply with any reasonable
            instructions following from the Licensor, if at any time You know or suspect that the Software is or may be subject to any error or has or may have provided inaccurate information.</td> </tr> <tr> <td>4.</td> <td colspan="2">Term</td> </tr> <tr>
            <td>4.1</td> <td colspan="2">Unless terminated under clause 4.2 or 4.3, this Licence shall last for as long as You continue to use the Software.</td> </tr> <tr> <td>4.2</td> <td colspan="2">This Licence shall terminate automatically if You fail to
            abide by any of its terms.</td> </tr> <tr> <td>4.3</td> <td colspan="2">This licence shall terminate automatically if the Licensor withdraws the Software.</td> </tr> <tr> <td>4.4</td> <td colspan="2">Upon termination of this Licence You shall
            destroy any documentation relating to the Software and erase all copies of the Software under Your control and stored on any medium.</td> </tr> <tr> <td>5.</td> <td colspan="2">Privacy</td> </tr> <tr> <td>5.1</td> <td colspan="2">We respect your
            personal information and your right to privacy.</td> </tr> <tr> <td>5.2</td> <td colspan="2">The&nbsp;<a href="http://www.gs1au.org/privacy.asp">GS1 Australia Privacy &amp; Security Policy</a> describes the information that may be collected by the
            Licensor, the choices you can make about your personal information and how the Licensor collects and protects your personal information.</td> </tr> <tr> <td>6.</td> <td colspan="2">Warranties and Remedies</td> </tr> <tr> <td>6.1</td> <td
            colspan="2">The Licensor warrants that any information provided by You will only be used for the following purposes:</td> </tr> <tr> <td>&nbsp;</td> <td>a)</td> <td>version control of data submissions; and</td> </tr> <tr> <td>&nbsp;</td>
            <td>b)</td> <td>input into the industry benchmarks whereby Your data will be sanitised to remove and means of identification before being utilised.</td> </tr> <tr> <td>6.2</td> <td colspan="2">The Licensor does not warrant that the Software will
            meet Your requirements or that the operation of the Software will be uninterrupted or error-free, or that all errors in the Software can be corrected.</td> </tr> <tr> <td>7.</td> <td colspan="2">Liabilities and Remedies</td> </tr> <tr> <td>7.1</td>
            <td colspan="2">You use the Software at Your own risk and in no event will the Licensor be liable to You for any liability, loss, expense, cost or damage of any kind (except personal injury or death resulting from the Licensor&#39;s negligence)
            including lost profits or any indirect, incidental, special exemplary or punitive loss or damage, or other consequential loss howsoever caused arising from the use of or inability to use the Software or from errors or deficiencies in it whether
            caused by negligence or otherwise, except as expressly provided in this Licence.</td> </tr> <tr> <td>7.2</td> <td colspan="2">To the fullest extent permitted by law, our liability under any guarantee, condition or warranty (including, without
            limitation, any guarantee, condition or warranty of merchantability, acceptable quality, fitness for purpose or fitness for disclosed result), or any other right or remedy, under any legislation or implied by any legislation (Statutory Warranties)
            is hereby excluded.</td> </tr> <tr> <td>7.3</td> <td colspan="2">The Licensor accepts no responsibility for the accuracy of the results obtained from the use of the Software. In using the Software You are expected to make final evaluation in the
            context of Your own environment.</td> </tr> <tr> <td>7.4</td> <td colspan="2">Save as otherwise provided in this Licence, You acknowledge and warrant that You are not in reliance on any statements, warranties or representations which may have been
            made by the Licensor or Your supplier or by anyone acting or purporting to act on behalf of any of them. All representations statements express or implied statutory or otherwise in respect of the software are expressly excluded.</td> </tr> <tr>
            <td>8.</td> <td colspan="2">Law</td> </tr> <tr> <td>&nbsp;</td> <td colspan="2">This Licence, which constitutes the entire agreement between You and the Licensor relating to the software, is governed by law in force in the State of Victoria. Each
            party submits to the non-exclusive jurisdiction of the courts of that place.</td> </tr> </tbody> </table>'
        );
        $manager->persist($tc);

        $manager->flush();
    }
}