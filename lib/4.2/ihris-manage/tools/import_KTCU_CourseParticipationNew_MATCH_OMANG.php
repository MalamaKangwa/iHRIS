#!/usr/bin/php
<?php
/*
 * © Copyright 2007, 2008 IntraHealth International, Inc.
 * 
 * This File is part of iHRIS
 * 
 * iHRIS is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * The page wrangler
 * 
 * This page loads the main HTML template for the home page of the site.
 * @package iHRIS
 * @subpackage DemoManage
 * @access public
 * @author Carl Leitner <litlfred@ibiblio.org>
 * @copyright Copyright &copy; 2007, 2008 IntraHealth International, Inc. 
 * @since Demo-v2.a
 * @version Demo-v2.a
 */


require_once("./import_base.php");



/*********************************************
*
*      Process Class
*
*********************************************/

class KTCU_CourseParticipationNew_MATCH_OMANG extends Processor {
    protected $course_ids;
    public function __construct($file) {        
        $this->course_ids = I2CE_FormStorage::search('training_course');        
        parent::__construct($file);
    }

    protected function getExpectedHeaders() {
        return  array(
            'course'=>'CourseCodeID',
            'course_name'=>'CourseDescription',
            'omang'=>'IDNo',
            'firstname'=>'Firstname',
            'middlename'=>'Middlename',
            'surname'=>'Surname',
            'start'=>'DateFrom',
            'end'=>'DateTo'
            );
    }

    protected static  $omang_id_type = 'id_type|1';
    protected static $excemption_id_type = 'id_type|2';

    protected function _processRow() {
        $this->process_stats_checked = array();
        if ( ($personId = $this->findPersonByID(self::$omang_id_type,$this->mapped_data['omang'],
              $this->mapped_data['surname'],$this->mapped_data['firstname'],$this->mapped_data['middlename'])) === false) {
            //$this->addBadRecord('Could not find/create person:' . $this->mapped_data['omang']);
            $this->processStats('bad_person');
            return false;
        }
        if ( ($sCourseID = $this->findScheduledCourse($this->mapped_data['course'],$this->mapped_data['start'],$this->mapped_data['end'])) === false) {
            //$this->addBadRecord('Could not find/create course');
            $this->processStats('bad_course');
            return false;
        }
        
        
        if ( ( $psCourseID = $this->assignToCourse($personId,$sCourseID)) === false) {
            $this->addBadRecord('Could not link person to course');
            $this->processStats('bad_course_link');
            return false;
        }
        //$this->assignScore($psCourseID,'final',$this->mapped_data['test']);
        //$this->assignScore($psCourseID,'pretest',$this->mapped_data['pretest']);
        //echo "Row: " . $this->row . "\n"  . print_r($this->process_stats,true) . "\n";
        if (count($this->duplicate_ids) > 0) {
            echo "Duplicates:" . implode(" ", $this->duplicate_ids) . "\n";
        }
        return true;
    }

    protected function assignScore($psCourseID,$exam_type,$exam_score) {
        if (!$exam_score) {
            return false;
        }
        if (! ($examObj = $this->ff->createContainer( 'training_course_exam'))instanceof iHRIS_Training_Course_Exam) {
            $this->processStats('cannot_create_tce');
            return false;
        }
        $examObj->setParent($psCourseID) ;
        $examObj->getField('score')->setValue($exam_score);
        $examObj->getField('training_course_exam_type')->setValue(array('training_course_exam_type',$exam_type));
        $examID = "training_course_exam|" . $this->save($examObj);
        $examObj->cleanup();
        return $examID;
    }
    

    function convertDate($date) {
        $parts = array_pad(explode("/",$date),3,'');
        foreach ($parts as $part) {
            if (strlen($part) == 0 || strlen($part) > 4) {                
                return false;
            }
            if (strlen($part) == 1) {
                $part = '0'. $part;
            }
        }
        list($m,$d,$y) = $parts;
        if ($m < 1 || $m > 12 || $d < 1 || $d>31 || $y < 1 || $y > 2013) {
            return false;
        }
        //converss "04/23/20130"  to "2010-04-23 00:00:00"
        return $y . '-' . $m . '-'. $d . ' 00:00:00';
    }
    function findScheduledCourse($course,$start,$end) {
        $course = 'KITSO'  .trim($course);
        echo "course code id is $course\n";
        $start = $this->convertDate($start);
        if (!$start) {
            $this->addBadRecord("Invalid start date, can't create this");
            return false;
        }
        $end = $this->convertDate($end);
        if (!$end) {
            $this->addBadRecord("Invalid end date");
            $this->processStats('bad_end_date');
            return false;
        }
        
        if (!in_array($course,$this->course_ids)) {
          $this->addBadRecord("creating new course id for $course");
            $trCObj = $this->ff->createContainer( 'training_course');
            $trCObj->name = trim($this->mapped_data['course_name']);
            $trCObj->setID($course);
            $trCObjId = $this->save($trCObj);
            $trCObj->cleanup();
            $this->course_ids[] = $trCObjId;
            return $course;
        }
        
        $where = array(
            'operator'=>'AND',
            'operand'=>array(
                0=>array(
                    'operator'=>'FIELD_LIMIT',
                    'field'=>'start_date',
                    'style'=>'equals',
                    'data'=>array(
                        'value'=>$start
                        )
                    ),
                1=>array(
                    'operator'=>'FIELD_LIMIT',
                    'field'=>'end_date',
                    'style'=>'equals',
                    'data'=>array(
                        'value'=>$end
                        )
                    ),
                2=>array(
                    'operator'=>'FIELD_LIMIT',
                    'field'=>'training_course',
                    'style'=>'equals',
                    'data'=>array(
                        'value'=>'training_course|'.$course
                        )
                    )
                )
            
            );
        
        
        
        $sCourse_ids = I2CE_FormStorage::search('scheduled_training_course',false,$where);
        if (count($sCourse_ids) > 1) {
            $this->processStats('duplicate_scheduled_course');
            print_r($sCourse_ids);
            return false;
        }
        if (count($sCourse_ids) == 1) {
            echo "course found, returning id ".current($sCourse_ids)."\n";
            return current($sCourse_ids);
        } 
        
        if (! ($sCourseObj = $this->ff->createContainer( 'scheduled_training_course')) instanceof iHRIS_Scheduled_Training_Course) {
          $this->addBadRecord("failed initialization");
            $this->processStats('cannot_create_stc');
            return false;
        }
        echo "course not found, creating new one.\n";
        $sCourseObj->getField('start_date')->setFromDB($start);
        $sCourseObj->getField('end_date')->setFromDB($end);
        $sCourseObj->getField('training_course')->setValue(array('training_course',$course));
        $sCourseID = $this->save($sCourseObj);
        $sCourseObj->cleanup();
        echo "returning course id as $sCourseID\n";
        return $sCourseID;
    }
    


    function assignToCourse($personID,$sCourseID) {
        if (! ($psCourseObj = $this->ff->createContainer( 'person_scheduled_training_course'))instanceof iHRIS_Person_Scheduled_Training_Course) {
            $this->processStats('cannot_create_pstc');
            return false;
        }
        $where_pstc = array(
            'operator'=>'AND',
            'operand'=>array(
                0=>array(
                    'operator'=>'FIELD_LIMIT',
                    'field'=>'parent',
                    'style'=>'equals',
                    'data'=>array(
                        'value'=>$personID
                        )
                    ),
                1=>array(
                    'operator'=>'FIELD_LIMIT',
                    'field'=>'scheduled_training_course',
                    'style'=>'equals',
                    'data'=>array(
                        'value'=>$sCourseID
                        )
                    )
                )
            );
        $hasAttendedThisCourse = I2CE_FormStorage::search('person_scheduled_training_course',false,$where_pstc);
        if(!empty($hasAttendedThisCourse)){
            $this->addBadRecord("This person is already assigned to this course");
            return false;
          }
        else{
          $psCourseObj->setParent($personID) ;
          $psCourseObj->getField('scheduled_training_course')->setValue(array('scheduled_training_course',$sCourseID));
          $psCourseID = "person_scheduled_training_course|" . $this->save($psCourseObj);
          $psCourseObj->cleanup();
          return $psCourseID;
        }
    }
    


    protected function processStats($stat) {
        //echo "Stat:$stat\n";
        if (!array_key_exists($stat,$this->process_stats)) {
            $this->process_stats[$stat] = 0;
        }
        if (in_array($stat,$this->process_stats_checked)) {
            return;
        }
        $this->process_stats[$stat]++;

    }
    
    protected $process_stats = array();
    protected $process_stats_checked = array();

    protected $duplicate_ids = array();

    function findPersonByID($id_type,$id_num, $surname, $firstname, $middle_name) {
        $id_num = strtoupper(trim($id_num));
        $id_type = self::$omang_id_type;
        $where_id_num = array(
            'operator'=>'AND',
            'operand'=>array(
                0=>array(
                    'operator'=>'FIELD_LIMIT',
                    'field'=>'id_type',
                    'style'=>'equals',
                    'data'=>array(
                        'value'=>$id_type
                        )
                    ),
                1=>array(
                    'operator'=>'FIELD_LIMIT',
                    'field'=>'id_num',
                    'style'=>'equals',
                    'data'=>array(
                        'value'=>$id_num
                        )
                    )
                )
            );
        $person_ids = I2CE_FormStorage::listFields('person_id',array('parent'),true,$where_id_num);
        
        $lname = strtolower(trim($surname));
        $fname = strtolower(trim($firstname));
        $where_names = array(
            'operator'=>'AND',
            'operand'=>array(
                0=>array(
                    'operator'=>'FIELD_LIMIT',
                    'field'=>'surname',
                    'style'=>'lowerequals',
                    'data'=>array(
                        'value'=>$lname
                        )
                    ),
                1=>array(
                    'operator'=>'FIELD_LIMIT',
                    'field'=>'firstname',
                    'style'=>'lowerequals',
                    'data'=>array(
                        'value'=>$fname
                        )
                    )
                )
            );
        $personIDs = I2CE_FormStorage::listFields('person',array('id'),false,$where_names);
        
        if (count($person_ids) > 1) {
            $this->processStats('duplicate_id');
            $this->addBadRecord("exists in duplicates");
            $this->duplicate_ids[] = $id_num;
            return false;
        } 
        elseif (count($person_ids) == 0 && count($personIDs) == 0) {
            $this->addBadRecord("creating a new person $firstname $surname");
            if (! ($personObj = $this->ff->createContainer( 'person')) instanceof iHRIS_Person) {
              echo "failed initialization\n";
                $this->processStats('cannot_create_person');
                return false;
            }
            $personObj->getField('surname')->setFromDB(trim($surname));
            $personObj->getField('firstname')->setFromDB(trim($firstname));
            $personObj->getField('othername')->setFromDB(trim($middle_name));
            $personID = 'person|' . $this->save($personObj);
            $personObj->cleanup();
            echo "saving omang for person\n";
            //set omang
            $idObj = $this->ff->createContainer('person_id');
            $idObj->getField('id_num')->setFromDB($id_num);
            $idObj->getField('id_type')->setValue(explode('|',$id_type));
            $idObj->setParent($personID);
            $this->save($idObj);
            $idObj->cleanup();
            
            echo "personID is $personID, now setting record status\n";
            $pRecordStatus = $this->ff->createContainer( 'person_recordstatus');
            $pRecordStatus->getField('incomplete')->setFromDB(1);
            $pRecordStatus->getField('duplicate')->setFromDB(0);
            $pRecordStatus->getField('incorrect')->setFromDB(0);
            $pRecordStatus->getField('comment')->setFromDB('Needs Review');
            $pRecordStatus->setParent( $personID );
            $this->save($pRecordStatus);
            $pRecordStatus->cleanup();
          return $personID;
        }
        elseif ( count($person_ids) == 0 && count($personIDs) == 1 ){
            //update Omang/Identification
            $personObjId = current($personIDs);
            echo "person Object ID is now set to ".$personObjId['id']."\n";
            $idObj = $this->ff->createContainer('person_id');
            $idObj->getField('id_num')->setFromDB($id_num);
            $idObj->getField('id_type')->setValue(explode('|',$id_type));
            $idObj->setParent($personObjId['id']);
            $this->save($idObj);
            $idObj->cleanup();
            return 'person|'.$personObjId['id'];
          }
        $data = current($person_ids);
        if (!is_array($data) || !array_key_exists('parent',$data) 
            ||  !is_string($data['parent']) 
            || !substr($data['parent'],0,7) == 'person|' 
            || !substr($data['parent'],7)) { 
            $this->processStats('person_not_found');
            return false;
        }
        $this->processStats('found_by_id');
        return $data['parent'];
    }




    


}




/*********************************************
*
*      Execute!
*
*********************************************/

//ini_set('memory_limit','4G');

if (count($arg_files) != 1) {
    usage("Please specify the name of a spreadsheet to process");
}

reset($arg_files);
$file = current($arg_files);
if($file[0] == '/') {
    $file = realpath($file);
} else {
    $file = realpath($dir. '/' . $file);
}
if (!is_readable($file)) {
    usage("Please specify the name of a spreadsheet to import: " . $file . " is not readable");
}

I2CE::raiseMessage("Loading from $file");


$processor = new KTCU_CourseParticipationNew_MATCH_OMANG($file);
$processor->run();

echo "Processing Statistics:\n";
print_r( $processor->getStats());




# Local Variables:
# mode: php
# c-default-style: "bsd"
# indent-tabs-mode: nil
# c-basic-offset: 4
# End:
