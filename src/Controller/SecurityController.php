<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MotdepasseType;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;

use MercurySeries\FlashyBundle\FlashyNotifier;
use phpDocumentor\Reflection\Types\Object_;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;




class SecurityController extends AbstractController
{

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function new(Request $request,UserPasswordEncoderInterface $encoder,FlashyNotifier $flashy)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $hash = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($hash);



                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();
                $flashy->success('Votre inscrirption a été faites!', 'http://your-awesome-link.com');


                //mailing
                $mail = new PHPMailer(true);

                try {

                    $username= $form->get('username')->getData();
                    $lastname = $form->get('lastname')->getData();
                    $firstname = $form->get('firstname')->getData();
                    $email = $form->get('email')->getData();
                    #dd($email);

                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'salem.haddad1998@gmail.com';             // SMTP username
                    $mail->Password   = '98375694';                               // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    //Recipients
                    $mail->setFrom('salemhaddad1998@gmail.com', 'Sport Lead Generation');
                    $mail->addAddress($email, 'Sport Lead Generation user');     // Add a recipient
                    // Content
                    $corps="Bonjour Monsieur/Madame  Voici Vos Coordonnées d inscription Mr: ".$username. "  Votre nom est: ".$lastname." Votre prenom est : " .$firstname ;
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Sois le Bienvenue dans Votre Salle de Sport en Ligne!';
                    $mail->Body    = $corps;

                    $mail->send();
                    $this->addFlash('message','the email has been sent');

                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                //end mailing





                return $this->redirectToRoute('security_login');
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils){


        return $this->render('security/login.html.twig');

    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/motdepasseoublie", name="motdepasseoublie")
     */
    public function mdpoublie(Request $request){




        $form = $this->createForm(MotdepasseType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {








//mailing
            $mail = new PHPMailer(true);

            try {
                $email = $form->get('email')->getData();
                dd($email);

                #$password = $form->get('password')->getData();


                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'salem.haddad1998@gmail.com';             // SMTP username
                $mail->Password   = '98375694';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('salemhaddad1998@gmail.com', 'Sport Lead Generation');
                $mail->addAddress($email, 'Sport Lead Generation user');     // Add a recipient
                // Content
                $corps="Bonjour Monsieur/Madame     Votre password est: ".$email  ;
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Voila votre mot de passe!';
                $mail->Body    = $corps;

                $mail->send();
                $this->addFlash('message','the email has been sent');

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

 return $this->redirectToRoute('security_login');
        }
        return $this->render('security/motdepasseoublie.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
