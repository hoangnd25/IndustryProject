<?php

namespace App\UserBundle\Entity;

use App\ResumeBundle\Entity\MemberProfile;
use App\ResumeBundle\Entity\StudentAccessCode;
use App\ResumeBundle\Entity\StudentProfile;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints as RollerworksPassword;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_STUDENT = 'ROLE_STUDENT';
    const ROLE_GS1_MEMBER = 'ROLE_GS1_MEMBER';

    const VISIBILITY_NOT_AVAILABLE = -1;
    const VISIBILITY_HIDDEN = 0;
    const VISIBILITY_VISIBLE = 1;

    public static function getRolesArray(){
        return array(
            User::ROLE_GS1_MEMBER => 'GS1 Member',
            User::ROLE_STUDENT => 'Student',
            User::ROLE_ADMIN => 'Admin'
        );
    }

    public static function getStudentProfileVisibilityArray(){
        return array(
            User::VISIBILITY_NOT_AVAILABLE => 'N/A',
            User::VISIBILITY_VISIBLE => 'Yes',
            User::VISIBILITY_HIDDEN => 'No'
        );
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=1)
     */
    protected $studentProfileVisibility;

    /**
     * @ORM\OneToOne(targetEntity="App\ResumeBundle\Entity\StudentProfile", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     **/
    protected $studentProfile;

    /**
     * @ORM\OneToOne(targetEntity="App\ResumeBundle\Entity\MemberProfile", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     **/
    protected $memberProfile;

//    /**
//     * @ORM\OneToOne(targetEntity="App\ResumeBundle\Entity\StudentAccessCode", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
//     **/
//    protected $activatedAccessCode;

    /**
     * @ORM\OneToMany(targetEntity="App\ResumeBundle\Entity\Shortlist", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     **/
    protected $shortlist;

    /**
     * @RollerworksPassword\PasswordRequirements(minLength="8", requireLetters=true, requireNumbers=true, requireCaseDiff=true)
     */
    protected $plainPassword;

    /**
     * @ORM\Column(length=100, nullable=true)
     * @Assert\Length(max="100")
     * @Assert\NotBlank()
     */
    protected $firstName;

    /**
     * @ORM\Column(length=100, nullable=true)
     * @Assert\Length(max="100")
     * @Assert\NotBlank()
     */

    protected $lastName;

    public function isStudent(){
        return $this->hasRole(User::ROLE_STUDENT);
    }

    public function isGS1Member(){
        return $this->hasRole(User::ROLE_GS1_MEMBER);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getType(){
        foreach($this->getRoles() as $role){
            if($role != User::ROLE_DEFAULT)
                return $role;
        }
        return User::ROLE_DEFAULT;
    }

    public function setType($type){
        $this->setRoles(array($type));
        if($type !== User::ROLE_STUDENT){
            $this->setStudentProfileVisibility(User::VISIBILITY_NOT_AVAILABLE);
        }else{
            $this->setStudentProfileVisibility(User::VISIBILITY_VISIBLE);
        }
    }

    /**
     * @return mixed
     */
    public function getStudentProfileVisibility()
    {
        return $this->studentProfileVisibility;
    }

    /**
     * @param mixed $studentProfileVisibility
     */
    public function setStudentProfileVisibility($studentProfileVisibility)
    {
        $this->studentProfileVisibility = $studentProfileVisibility;
    }

    public function getVisible(){
        if(!$this->isStudent()){
            return null;
        }else{
            return $this->getStudentProfileVisibility() == User::VISIBILITY_VISIBLE ? true : false;
        }
    }

    public function setVisible($value){
        /** @var User $user */
        if(!$this->isStudent()){
            $this->setStudentProfileVisibility(User::VISIBILITY_NOT_AVAILABLE);
        }else{
            $this->setStudentProfileVisibility($value ? User::VISIBILITY_VISIBLE : User::VISIBILITY_HIDDEN);
        }
    }

    /**
     * @return StudentProfile
     */
    public function getStudentProfile()
    {
        return $this->studentProfile;
    }

    /**
     * @param mixed $studentProfile
     */
    public function setStudentProfile($studentProfile)
    {
        $this->studentProfile = $studentProfile;
        if($studentProfile != null && $studentProfile instanceof StudentProfile)
            $this->studentProfile->setUser($this);
    }

    /**
     * @return mixed
     */
    public function getMemberProfile()
    {
        return $this->memberProfile;
    }

    /**
     * @param mixed $memberProfile
     */
    public function setMemberProfile($memberProfile)
    {
        $this->memberProfile = $memberProfile;
        if($memberProfile != null && $memberProfile instanceof MemberProfile)
            $this->memberProfile->setUser($this);
    }

//    /**
//     * @return mixed
//     */
//    public function getActivatedAccessCode()
//    {
//        return $this->activatedAccessCode;
//    }
//
//    /**
//     * @param mixed $code
//     */
//    public function setActivatedAccessCode($code)
//    {
//        if($code !== null && $code instanceof StudentAccessCode){
//            $this->activatedAccessCode = $code;
//            $this->activatedAccessCode->setActivated(true);
//            $this->activatedAccessCode->setUser($this);
//
//            if($this->activatedAccessCode->getCode())
//                $this->setStudentProfileVisibility(true);
//        }
//        $this->activatedAccessCode = null;
//    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        $this->setUsername($email);

        return $this;
    }

    public function __construct()
    {
        parent::__construct();

        // Set default user account type to student
        $this->setType(User::ROLE_STUDENT);
        $this->setStudentProfileVisibility(User::VISIBILITY_VISIBLE);
    }

    public function getReadableType(){
        $type = $this->getType();
        switch ($type) {
            case User::ROLE_ADMIN:
                return 'Admin';
            case User::ROLE_STUDENT:
                return 'Student';
            case User::ROLE_GS1_MEMBER:
                return 'Member';
            default:
                return 'Unknown';
        }
    }

    public function getReadableEnabled(){
        return $this->isEnabled() ? 'Yes' : 'No';
    }

    public function getReadableStudentVisibility(){
        $visibility = $this->getVisible();
        if($visibility != null){
            return $visibility ? 'Yes' : 'No';
        }else{
            return 'N/A';
        }
    }
}