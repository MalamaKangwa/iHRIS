<?php namespace DSTU_TWO\FHIRResource\FHIRPatient;

/*!
 * This class was generated with the PHPFHIR library (https://github.com/dcarbone/php-fhir) using
 * class definitions from HL7 FHIR (https://www.hl7.org/fhir/)
 * 
 * Class creation date: May 13th, 2016
 * 
 * PHPFHIR Copyright:
 * 
 * Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *        http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 *
 * FHIR Copyright Notice:
 *
 *   Copyright (c) 2011+, HL7, Inc.
 *   All rights reserved.
 * 
 *   Redistribution and use in source and binary forms, with or without modification,
 *   are permitted provided that the following conditions are met:
 * 
 *    * Redistributions of source code must retain the above copyright notice, this
 *      list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright notice,
 *      this list of conditions and the following disclaimer in the documentation
 *      and/or other materials provided with the distribution.
 *    * Neither the name of HL7 nor the names of its contributors may be used to
 *      endorse or promote products derived from this software without specific
 *      prior written permission.
 * 
 *   THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 *   ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *   WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 *   IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 *   INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 *   NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 *   PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 *   WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 *   ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 *   POSSIBILITY OF SUCH DAMAGE.
 * 
 * 
 *   Generated on Sat, Oct 24, 2015 07:41+1100 for FHIR v1.0.2
 * 
 *   Note: the schemas & schematrons do not contain all of the rules about what makes resources
 *   valid. Implementers will still need to be familiar with the content of the specification and with
 *   any profiles that apply to the resources in order to make a conformant implementation.
 * 
 */

use DSTU_TWO\FHIRElement\FHIRBackboneElement;
use DSTU_TWO\JsonSerializable;

/**
 * Demographics and other administrative information about an individual or animal receiving care or other health-related services.
 */
class FHIRPatientContact extends FHIRBackboneElement implements JsonSerializable
{
    /**
     * The nature of the relationship between the patient and the contact person.
     * @var \DSTU_TWO\FHIRElement\FHIRCodeableConcept[]
     */
    public $relationship = array();

    /**
     * A name associated with the contact person.
     * @var \DSTU_TWO\FHIRElement\FHIRHumanName
     */
    public $name = null;

    /**
     * A contact detail for the person, e.g. a telephone number or an email address.
     * @var \DSTU_TWO\FHIRElement\FHIRContactPoint[]
     */
    public $telecom = array();

    /**
     * Address for the contact person.
     * @var \DSTU_TWO\FHIRElement\FHIRAddress
     */
    public $address = null;

    /**
     * Administrative Gender - the gender that the contact person is considered to have for administration and record keeping purposes.
     * @var \DSTU_TWO\FHIRElement\FHIRCode
     */
    public $gender = null;

    /**
     * Organization on behalf of which the contact is acting or for which the contact is working.
     * @var \DSTU_TWO\FHIRElement\FHIRReference
     */
    public $organization = null;

    /**
     * The period during which this contact person or organization is valid to be contacted relating to this patient.
     * @var \DSTU_TWO\FHIRElement\FHIRPeriod
     */
    public $period = null;

    /**
     * @var string
     */
    private $_fhirElementName = 'Patient.Contact';

    /**
     * The nature of the relationship between the patient and the contact person.
     * @return \DSTU_TWO\FHIRElement\FHIRCodeableConcept[]
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * The nature of the relationship between the patient and the contact person.
     * @param \DSTU_TWO\FHIRElement\FHIRCodeableConcept[] $relationship
     * @return $this
     */
    public function addRelationship($relationship)
    {
        $this->relationship[] = $relationship;
        return $this;
    }

    /**
     * A name associated with the contact person.
     * @return \DSTU_TWO\FHIRElement\FHIRHumanName
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * A name associated with the contact person.
     * @param \DSTU_TWO\FHIRElement\FHIRHumanName $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * A contact detail for the person, e.g. a telephone number or an email address.
     * @return \DSTU_TWO\FHIRElement\FHIRContactPoint[]
     */
    public function getTelecom()
    {
        return $this->telecom;
    }

    /**
     * A contact detail for the person, e.g. a telephone number or an email address.
     * @param \DSTU_TWO\FHIRElement\FHIRContactPoint[] $telecom
     * @return $this
     */
    public function addTelecom($telecom)
    {
        $this->telecom[] = $telecom;
        return $this;
    }

    /**
     * Address for the contact person.
     * @return \DSTU_TWO\FHIRElement\FHIRAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Address for the contact person.
     * @param \DSTU_TWO\FHIRElement\FHIRAddress $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Administrative Gender - the gender that the contact person is considered to have for administration and record keeping purposes.
     * @return \DSTU_TWO\FHIRElement\FHIRCode
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Administrative Gender - the gender that the contact person is considered to have for administration and record keeping purposes.
     * @param \DSTU_TWO\FHIRElement\FHIRCode $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Organization on behalf of which the contact is acting or for which the contact is working.
     * @return \DSTU_TWO\FHIRElement\FHIRReference
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Organization on behalf of which the contact is acting or for which the contact is working.
     * @param \DSTU_TWO\FHIRElement\FHIRReference $organization
     * @return $this
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * The period during which this contact person or organization is valid to be contacted relating to this patient.
     * @return \DSTU_TWO\FHIRElement\FHIRPeriod
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * The period during which this contact person or organization is valid to be contacted relating to this patient.
     * @param \DSTU_TWO\FHIRElement\FHIRPeriod $period
     * @return $this
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * @return string
     */
    public function get_fhirElementName()
    {
        return $this->_fhirElementName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->get_fhirElementName();
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = parent::jsonSerialize();
        if (0 < count($this->relationship)) {
            $json['relationship'] = array();
            foreach($this->relationship as $relationship) {
                $json['relationship'][] = $relationship->jsonSerialize();
            }
        }
        if (null !== $this->name) $json['name'] = $this->name->jsonSerialize();
        if (0 < count($this->telecom)) {
            $json['telecom'] = array();
            foreach($this->telecom as $telecom) {
                $json['telecom'][] = $telecom->jsonSerialize();
            }
        }
        if (null !== $this->address) $json['address'] = $this->address->jsonSerialize();
        if (null !== $this->gender) $json['gender'] = $this->gender->jsonSerialize();
        if (null !== $this->organization) $json['organization'] = $this->organization->jsonSerialize();
        if (null !== $this->period) $json['period'] = $this->period->jsonSerialize();
        return $json;
    }

    /**
     * @param boolean $returnSXE
     * @param \SimpleXMLElement $sxe
     * @return string|\SimpleXMLElement
     */
    public function xmlSerialize($returnSXE = false, $sxe = null)
    {
        if (null === $sxe) $sxe = new \SimpleXMLElement('<PatientContact xmlns="http://hl7.org/fhir"></PatientContact>');
        parent::xmlSerialize(true, $sxe);
        if (0 < count($this->relationship)) {
            foreach($this->relationship as $relationship) {
                $relationship->xmlSerialize(true, $sxe->addChild('relationship'));
            }
        }
        if (null !== $this->name) $this->name->xmlSerialize(true, $sxe->addChild('name'));
        if (0 < count($this->telecom)) {
            foreach($this->telecom as $telecom) {
                $telecom->xmlSerialize(true, $sxe->addChild('telecom'));
            }
        }
        if (null !== $this->address) $this->address->xmlSerialize(true, $sxe->addChild('address'));
        if (null !== $this->gender) $this->gender->xmlSerialize(true, $sxe->addChild('gender'));
        if (null !== $this->organization) $this->organization->xmlSerialize(true, $sxe->addChild('organization'));
        if (null !== $this->period) $this->period->xmlSerialize(true, $sxe->addChild('period'));
        if ($returnSXE) return $sxe;
        return $sxe->saveXML();
    }


}