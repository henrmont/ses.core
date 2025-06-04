<?php

namespace App\Http\Controllers;

use App\Models\AttentionNetwork;
use App\Models\BedType;
use App\Models\Cid;
use App\Models\Competence;
use App\Models\ConditionedRule;
use App\Models\Detail;
use App\Models\DetailDescription;
use App\Models\Financing;
use App\Models\Group;
use App\Models\Heading;
use App\Models\License;
use App\Models\LicenseGroup;
use App\Models\Modality;
use App\Models\NetworkComponent;
use App\Models\Ocupation;
use App\Models\OrganizationForm;
use App\Models\Procedure;
use App\Models\ProcedureCompatible;
use App\Models\ProcedureDescription;
use App\Models\ProcedureException;
use App\Models\ProcedureIncrement;
use App\Models\ProcedureModality;
use App\Models\Register;
use App\Models\Renases;
use App\Models\Service;
use App\Models\ServiceClassification;
use App\Models\SiaSih;
use App\Models\Subgroup;
use App\Models\Tuss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class SigtapController extends Controller
{
    private function getImportedCompetence($file) {
        $filename = explode('_', $file->getClientOriginalName());
        return $filename[1];
    }

    private function getImportedCids($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_cid.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,4))),
                        'name' => utf8_encode(trim(substr($line,4,100))),
                        'grievance' => utf8_encode(trim(substr($line,104,1))),
                        'gender' => utf8_encode(trim(substr($line,105,1))),
                        'stadium' => utf8_encode(trim(substr($line,106,1))),
                        'irradiated_fields' => utf8_encode(trim(substr($line,107,4))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedNetworkComponents($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_componente_rede.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,10))),
                        'name' => utf8_encode(trim(substr($line,10,150))),
                        'attention_network_code' => utf8_encode(trim(substr($line,160,3))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedAttentionNetworks($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_rede_atencao.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,3))),
                        'name' => utf8_encode(trim(substr($line,3,50))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureDescriptions($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_descricao.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'description' => utf8_encode(trim(substr($line,10,4000))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedures($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_procedimento.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,10))),
                        'name' => utf8_encode(trim(substr($line,10,250))),
                        'complexity' => utf8_encode(trim(substr($line,260,1))),
                        'gender' => utf8_encode(trim(substr($line,261,1))),
                        'max_execution' => utf8_encode(trim(substr($line,262,4))),
                        'days_of_stay' => utf8_encode(trim(substr($line,266,4))),
                        'points' => utf8_encode(trim(substr($line,270,4))),
                        'min_age' => utf8_encode(trim(substr($line,274,4))),
                        'max_age' => utf8_encode(trim(substr($line,278,4))),
                        'hospital_procedure_value' => utf8_encode(trim(substr($line,282,10))),
                        'outpatient_procedure_value' => utf8_encode(trim(substr($line,292,10))),
                        'profissional_procedure_value' => utf8_encode(trim(substr($line,302,10))),
                        'financing_code' => utf8_encode(trim(substr($line,312,2))),
                        'heading_code' => utf8_encode(trim(substr($line,314,6))),
                        'time_of_stay' => utf8_encode(trim(substr($line,320,4))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedGroups($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_grupo.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,2))),
                        'name' => utf8_encode(trim(substr($line,2,100))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedSubgroups($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_sub_grupo.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,4))),
                        'name' => utf8_encode(trim(substr($line,4,100))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedOrganizationForms($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_forma_organizacao.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,6))),
                        'name' => utf8_encode(trim(substr($line,6,100))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedFinancings($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_financiamento.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,2))),
                        'name' => utf8_encode(trim(substr($line,2,100))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedHeadings($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_rubrica.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,6))),
                        'name' => utf8_encode(trim(substr($line,6,100))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedDetailDescriptions($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_descricao_detalhe.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'detail_code' => utf8_encode(trim(substr($line,0,3))),
                        'description' => utf8_encode(trim(substr($line,3,4000))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedDetails($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_detalhe.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,3))),
                        'name' => utf8_encode(trim(substr($line,3,100))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedLicenseGroups($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_grupo_habilitacao.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,4))),
                        'name' => utf8_encode(trim(substr($line,4,20))),
                        'description' => utf8_encode(trim(substr($line,24,250))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedLicenses($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_habilitacao.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,4))),
                        'name' => utf8_encode(trim(substr($line,4,150))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedModalities($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_modalidade.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,2))),
                        'name' => utf8_encode(trim(substr($line,2,100))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedOcupations($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_ocupacao.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,6))),
                        'name' => utf8_encode(trim(substr($line,6,150))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedRegisters($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_registro.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,2))),
                        'name' => utf8_encode(trim(substr($line,2,50))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedConditionedRules($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_regra_condicionada.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,4))),
                        'name' => utf8_encode(trim(substr($line,4,150))),
                        'description' => utf8_encode(trim(substr($line,154,4000))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedRenasess($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_renases.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,10))),
                        'name' => utf8_encode(trim(substr($line,10,150))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedServices($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_servico.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,3))),
                        'name' => utf8_encode(trim(substr($line,3,120))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedServiceClassifications($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_servico_classificacao.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'service_code' => utf8_encode(trim(substr($line,0,3))),
                        'code' => utf8_encode(trim(substr($line,3,3))),
                        'name' => utf8_encode(trim(substr($line,6,150))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedSiaSihs($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_sia_sih.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,10))),
                        'name' => utf8_encode(trim(substr($line,10,100))),
                        'type' => utf8_encode(trim(substr($line,110,1))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedBedTypes($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_tipo_leito.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,2))),
                        'name' => utf8_encode(trim(substr($line,2,60))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedTusses($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'tb_tuss.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'code' => utf8_encode(trim(substr($line,0,10))),
                        'name' => utf8_encode(trim(substr($line,10,450))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureCids($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_cid.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'cid_code' => utf8_encode(trim(substr($line,10,4))),
                        'main' => utf8_encode(trim(substr($line,14,1))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureNetworkComponents($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_comp_rede.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'network_component_code' => utf8_encode(trim(substr($line,10,10))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureCompatibles($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_compativel.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'register_code' => utf8_encode(trim(substr($line,10,2))),
                        'compatible_procedure_code' => utf8_encode(trim(substr($line,12,10))),
                        'compatible_register_code' => utf8_encode(trim(substr($line,22,2))),
                        'type' => utf8_encode(trim(substr($line,24,1))),
                        'amount' => utf8_encode(trim(substr($line,25,4))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureDetails($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_detalhe.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'detail_code' => utf8_encode(trim(substr($line,10,3))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureLicenses($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_habilitacao.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'license_code' => utf8_encode(trim(substr($line,10,4))),
                        'license_group_code' => utf8_encode(trim(substr($line,14,4))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureIncrements($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_incremento.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'license_code' => utf8_encode(trim(substr($line,10,4))),
                        'hospital_percentage_procedure_value' => utf8_encode(trim(substr($line,14,7))),
                        'outpatient_percentage_procedure_value' => utf8_encode(trim(substr($line,21,7))),
                        'profissional_percentage_procedure_value' => utf8_encode(trim(substr($line,28,7))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureBedTypes($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_leito.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'bed_type_code' => utf8_encode(trim(substr($line,10,2))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureModalities($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_modalidade.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'modality_code' => utf8_encode(trim(substr($line,10,2))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureOcupations($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_ocupacao.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'ocupation_code' => utf8_encode(trim(substr($line,10,6))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureOrigins($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_origem.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'origin_procedure_code' => utf8_encode(trim(substr($line,10,10))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureRegisters($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_registro.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'register_code' => utf8_encode(trim(substr($line,10,2))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureConditionedRules($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_regra_cond.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'conditioned_rule_code' => utf8_encode(trim(substr($line,10,4))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureRenasess($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_renases.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'renases_code' => utf8_encode(trim(substr($line,10,10))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureServices($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_servico.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'service_code' => utf8_encode(trim(substr($line,10,3))),
                        'service_classification_code' => utf8_encode(trim(substr($line,13,3))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureSiaSihs($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_sia_sih.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'sia_sih_code' => utf8_encode(trim(substr($line,10,10))),
                        'type' => utf8_encode(trim(substr($line,20,1))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureTusses($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_procedimento_tuss.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'tuss_code' => utf8_encode(trim(substr($line,10,10))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    private function getImportedProcedureExceptions($file) {
        $data = [];

        $zip = new ZipArchive;
        $zip->open($file);

        set_time_limit(0);
        for($i = 0;$i < $zip->numFiles; $i++ ){
            $filename = $zip->getNameIndex($i);
            if ($filename === 'rl_excecao_compatibilidade.txt') {
                foreach (file("zip://".$file."#".$filename) as $line) {
                    $reg = [
                        'restriction_procedure_code' => utf8_encode(trim(substr($line,0,10))),
                        'procedure_code' => utf8_encode(trim(substr($line,10,10))),
                        'register_code' => utf8_encode(trim(substr($line,20,2))),
                        'compatible_procedure_code' => utf8_encode(trim(substr($line,22,10))),
                        'compatible_register_code' => utf8_encode(trim(substr($line,32,2))),
                        'type' => utf8_encode(trim(substr($line,34,1))),
                    ];

                    array_push($data, $reg);
                }
            }
        }

        return $data;
    }

    public function process(Request $request)
    {
        set_time_limit(0);
        if (is_file($request->file('file'))) {
            $request->validate([
                'file' => 'required',
                'file.*' => 'required|mimes:zip',
            ]);

            $imported_competence = $this->getImportedCompetence($request->file('file'));
            $competence = Competence::where('name', $imported_competence)->first();
            DB::connection('sigtap')->beginTransaction();
            if (empty($competence)) {
                $competence = Competence::create([
                    'name' => $imported_competence,
                ]);

                $imported_groups = $this->getImportedGroups($request->file('file'));
                $imported_subgroups = $this->getImportedSubgroups($request->file('file'));
                $imported_organization_forms = $this->getImportedOrganizationForms($request->file('file'));
                $imported_financings = $this->getImportedFinancings($request->file('file'));
                $imported_modalities = $this->getImportedModalities($request->file('file'));
                $imported_headings = $this->getImportedHeadings($request->file('file'));
                $imported_procedures = $this->getImportedProcedures($request->file('file'));
                $imported_cids = $this->getImportedCids($request->file('file'));
                $imported_bed_types = $this->getImportedBedTypes($request->file('file'));
                $imported_conditioned_rules = $this->getImportedConditionedRules($request->file('file'));
                $imported_details = $this->getImportedDetails($request->file('file'));
                $imported_licenses = $this->getImportedLicenses($request->file('file'));
                $imported_license_groups = $this->getImportedLicenseGroups($request->file('file'));
                $imported_network_components = $this->getImportedNetworkComponents($request->file('file'));
                $imported_ocupations = $this->getImportedOcupations($request->file('file'));
                $imported_registers = $this->getImportedRegisters($request->file('file'));
                $imported_attention_network = $this->getImportedAttentionNetworks($request->file('file'));
                $imported_renasess = $this->getImportedRenasess($request->file('file'));
                $imported_services = $this->getImportedServices($request->file('file'));
                $imported_service_classifications = $this->getImportedServiceClassifications($request->file('file'));
                $imported_sia_sihs = $this->getImportedSiaSihs($request->file('file'));
                $imported_tusses = $this->getImportedTusses($request->file('file'));
                $imported_detail_descriptions = $this->getImportedDetailDescriptions($request->file('file'));
                $imported_procedure_descriptions = $this->getImportedProcedureDescriptions($request->file('file'));

                $imported_procedure_modalities = $this->getImportedProcedureModalities($request->file('file'));
                $imported_procedure_cids = $this->getImportedProcedureCids($request->file('file'));
                $imported_procedure_bed_types = $this->getImportedProcedureBedTypes($request->file('file'));
                $imported_procedure_conditioned_rules = $this->getImportedProcedureConditionedRules($request->file('file'));
                $imported_procedure_details = $this->getImportedProcedureDetails($request->file('file'));
                $imported_procedure_licenses = $this->getImportedProcedureLicenses($request->file('file'));
                $imported_procedure_network_components = $this->getImportedProcedureNetworkComponents($request->file('file'));
                $imported_procedure_ocupations = $this->getImportedProcedureOcupations($request->file('file'));
                $imported_procedure_registers = $this->getImportedProcedureRegisters($request->file('file'));
                $imported_procedure_renasess = $this->getImportedProcedureRenasess($request->file('file'));
                $imported_procedure_services = $this->getImportedProcedureServices($request->file('file'));
                $imported_procedure_sia_sihs = $this->getImportedProcedureSiaSihs($request->file('file'));
                $imported_procedure_tusses = $this->getImportedProcedureTusses($request->file('file'));
                $imported_procedure_compatibles = $this->getImportedProcedureCompatibles($request->file('file'));
                $imported_procedure_increments = $this->getImportedProcedureIncrements($request->file('file'));
                $imported_procedure_origins = $this->getImportedProcedureOrigins($request->file('file'));
                $imported_procedure_exceptions = $this->getImportedProcedureExceptions($request->file('file'));

                foreach ($imported_groups as $item) {
                    Group::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_subgroups as $item) {
                    $group = Group::where('code', substr($item['code'],0,2))->where('competence_id',$competence->id)->first();
                    Subgroup::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'group_id' => $group->id,
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_organization_forms as $item) {
                    $subgroup = Subgroup::where('code', substr($item['code'],0,4))->where('competence_id',$competence->id)->first();
                    OrganizationForm::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'group_id' => $subgroup->group_id,
                        'subgroup_id' => $subgroup->id,
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_financings as $item) {
                    Financing::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_headings as $item) {
                    Heading::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedures as $item) {
                    $organization_form = OrganizationForm::where('code', substr($item['code'],0,6))->where('competence_id',$competence->id)->first();
                    $financing = Financing::where('code', $item['financing_code'])->where('competence_id',$competence->id)->first();
                    $heading = Heading::where('code', $item['heading_code'])->where('competence_id',$competence->id)->first();
                    Procedure::create([
                        'competence_id' => $competence->id,
                        'group_id' => $organization_form->group_id,
                        'subgroup_id' => $organization_form->subgroup_id,
                        'organization_form_id' => $organization_form->id,
                        'financing_id' => $financing->id,
                        'heading_id' => $heading ? $heading->id : null,
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'complexity' => $item['complexity'],
                        'gender' => $item['gender'],
                        'max_execution' => $item['max_execution'],
                        'days_of_stay' => $item['days_of_stay'],
                        'time_of_stay' => $item['time_of_stay'],
                        'points' => $item['points'],
                        'min_age' => $item['min_age'],
                        'max_age' => $item['max_age'],
                        'hospital_procedure_value' => floatval($item['hospital_procedure_value'])/100,
                        'outpatient_procedure_value' => floatval($item['outpatient_procedure_value'])/100,
                        'profissional_procedure_value' => floatval($item['profissional_procedure_value'])/100,
                    ]);
                }

                foreach ($imported_modalities as $item) {
                    Modality::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_modalities as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $modality = Modality::where('code', $item['modality_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_modalities')->insert([
                        'procedure_id' => $procedure->id,
                        'modality_id' => $modality->id,
                    ]);
                }

                foreach ($imported_cids as $item) {
                    Cid::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'grievance' => $item['grievance'],
                        'gender' => $item['gender'],
                        'stadium' => $item['stadium'],
                        'irradiated_fields' => $item['irradiated_fields'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_cids as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $cid = Cid::where('code', $item['cid_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_cids')->insert([
                        'procedure_id' => $procedure->id,
                        'cid_id' => $cid->id,
                        'is_main' => $item['main'],
                    ]);
                }

                foreach ($imported_bed_types as $item) {
                    BedType::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_bed_types as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $bed_type = BedType::where('code', $item['bed_type_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_bed_types')->insert([
                        'procedure_id' => $procedure->id,
                        'bed_type_id' => $bed_type->id,
                    ]);
                }

                foreach ($imported_conditioned_rules as $item) {
                    ConditionedRule::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_conditioned_rules as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $conditioned_rule = ConditionedRule::where('code', $item['conditioned_rule_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_conditioned_rules')->insert([
                        'procedure_id' => $procedure->id,
                        'conditioned_rule_id' => $conditioned_rule->id,
                    ]);
                }

                foreach ($imported_details as $item) {
                    Detail::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_details as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $detail = Detail::where('code', $item['detail_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_details')->insert([
                        'procedure_id' => $procedure->id,
                        'detail_id' => $detail->id,
                    ]);
                }

                foreach ($imported_licenses as $item) {
                    License::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_license_groups as $item) {
                    LicenseGroup::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_licenses as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $license = License::where('code', $item['license_code'])->where('competence_id',$competence->id)->first();
                    $license_group = LicenseGroup::where('code', $item['license_group_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_licenses')->insert([
                        'procedure_id' => $procedure->id,
                        'license_id' => $license->id,
                        'license_group_id' => $license_group ? $license_group->id : null,
                    ]);
                }

                foreach ($imported_attention_network as $item) {
                    AttentionNetwork::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_network_components as $item) {
                    $attention_network = AttentionNetwork::where('code', $item['attention_network_code'])->where('competence_id',$competence->id)->first();
                    NetworkComponent::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'attention_network_id' => $attention_network->id,
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_network_components as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $network_component = NetworkComponent::where('code', $item['network_component_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_network_components')->insert([
                        'procedure_id' => $procedure->id,
                        'network_component_id' => $network_component->id,
                    ]);
                }

                foreach ($imported_ocupations as $item) {
                    Ocupation::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_ocupations as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $ocupation = Ocupation::where('code', $item['ocupation_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_ocupations')->insert([
                        'procedure_id' => $procedure->id,
                        'ocupation_id' => $ocupation->id,
                    ]);
                }

                foreach ($imported_registers as $item) {
                    Register::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_registers as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $register = Register::where('code', $item['register_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_registers')->insert([
                        'procedure_id' => $procedure->id,
                        'register_id' => $register->id,
                    ]);
                }

                foreach ($imported_renasess as $item) {
                    Renases::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_renasess as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $renases = Renases::where('code', $item['renases_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_renasess')->insert([
                        'procedure_id' => $procedure->id,
                        'renases_id' => $renases->id,
                    ]);
                }

                foreach ($imported_services as $item) {
                    Service::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_service_classifications as $item) {
                    $service = Service::where('code', $item['service_code'])->where('competence_id',$competence->id)->first();
                    ServiceClassification::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'service_id' => $service->id,
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_services as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $service = Service::where('code', $item['service_code'])->where('competence_id',$competence->id)->first();
                    $service_classification = ServiceClassification::where('code', $item['service_classification_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_services')->insert([
                        'procedure_id' => $procedure->id,
                        'service_id' => $service->id,
                        'service_classification_id' => $service_classification->id,
                    ]);
                }

                foreach ($imported_sia_sihs as $item) {
                    SiaSih::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'type' => $item['type'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_sia_sihs as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $sia_sih = SiaSih::where('code', $item['sia_sih_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_sia_sihs')->insert([
                        'procedure_id' => $procedure->id,
                        'sia_sih_id' => $sia_sih->id,
                        'type' => $item['type'],
                    ]);
                }

                foreach ($imported_tusses as $item) {
                    Tuss::create([
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_tusses as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $tuss = Tuss::where('code', $item['tuss_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_tusses')->insert([
                        'procedure_id' => $procedure->id,
                        'tuss_id' => $tuss->id,
                    ]);
                }

                foreach ($imported_detail_descriptions as $item) {
                    $detail = Detail::where('code', $item['detail_code'])->where('competence_id',$competence->id)->first();
                    DetailDescription::create([
                        'detail_id' => $detail->id,
                        'description' => $item['description'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_descriptions as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    ProcedureDescription::create([
                        'procedure_id' => $procedure->id,
                        'description' => $item['description'],
                        'competence_id' => $competence->id,
                    ]);
                }

                foreach ($imported_procedure_compatibles as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $register = Register::where('code', $item['register_code'])->where('competence_id',$competence->id)->first();
                    $compatible_procedure = Procedure::where('code', $item['compatible_procedure_code'])->where('competence_id',$competence->id)->first();
                    $compatible_register = Register::where('code', $item['compatible_register_code'])->where('competence_id',$competence->id)->first();
                    ProcedureCompatible::create([
                        'procedure_id' => $procedure->id,
                        'register_id' => $register->id,
                        'compatible_procedure_id' => $compatible_procedure->id,
                        'compatible_register_id' => $compatible_register->id,
                        'type' => trim($item['type']),
                        'amount' => $item['amount'],
                    ]);
                }

                foreach ($imported_procedure_increments as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $license = License::where('code', $item['license_code'])->where('competence_id',$competence->id)->first();
                    ProcedureIncrement::create([
                        'procedure_id' => $procedure->id,
                        'license_id' => $license->id,
                        'hospital_percentage_procedure_value' => $item['hospital_percentage_procedure_value'],
                        'outpatient_percentage_procedure_value' => $item['outpatient_percentage_procedure_value'],
                        'profissional_percentage_procedure_value' => $item['profissional_percentage_procedure_value'],
                    ]);
                }

                foreach ($imported_procedure_origins as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $origin_procedure = Procedure::where('code', $item['origin_procedure_code'])->where('competence_id',$competence->id)->first();
                    DB::connection('sigtap')->table('procedure_origins')->insert([
                        'procedure_id' => $procedure->id,
                        'origin_procedure_id' => $origin_procedure->id,
                    ]);
                }

                foreach ($imported_procedure_exceptions as $item) {
                    $procedure = Procedure::where('code', $item['procedure_code'])->where('competence_id',$competence->id)->first();
                    $restriction_procedure = Procedure::where('code', $item['restriction_procedure_code'])->where('competence_id',$competence->id)->first();
                    $compatible_procedure = Procedure::where('code', $item['compatible_procedure_code'])->where('competence_id',$competence->id)->first();
                    $register = Register::where('code', $item['register_code'])->where('competence_id',$competence->id)->first();
                    $compatible_register = Register::where('code', $item['compatible_register_code'])->where('competence_id',$competence->id)->first();
                    ProcedureException::create([
                        'restriction_procedure_id' => $restriction_procedure->id,
                        'procedure_id' => $procedure->id,
                        'compatible_procedure_id' => $compatible_procedure->id,
                        'register_id' => $register->id,
                        'compatible_register_id' => $compatible_register->id,
                        'type' => $item['type'],
                    ]);
                }

                DB::connection('sigtap')->commit();
                return response()->json(['message' => 'Competência importada com sucesso!'], 200);
            } else {
                DB::connection('sigtap')->rollBack();
                return response()->json(['message' => 'Competência já importada'], 400);
            }

        }
    }

    public function getCompetences()
    {
        $competences = Competence::get();
        return response()->json($competences, 200);
    }

    public function getProcedures($competence, $limit)
    {
        set_time_limit(0);
        $procedures = Procedure::with(
            'group',
            'subgroup',
            'organization_form',
            'financing',
            'heading',
            'description',
            'modalities',
            'cids',
            'bed_types',
            'conditioned_rules',
            'details.description',
            'licenses',
            'license_groups',
            'network_components',
            'ocupations',
            'registers',
            'renasess',
            'services.service_classification',
            'sia_sihs',
            'tusses',
            'compatibles.register',
            'compatibles.compatible_procedure',
            'compatibles.compatible_register',
            'increments.license',
            'origin',
            'exception.restriction_procedure',
            'exception.compatible_procedure',
            'exception.register',
            'exception.compatible_register',
            )->where('competence_id',$competence)->limit($limit)->get();
        return response()->json($procedures, 200);
    }

    public function getAllProcedures($competence)
    {
        set_time_limit(0);
        $procedures = Procedure::with(
            'group',
            'subgroup',
            'organization_form',
            'financing',
            'heading',
            'description',
            'modalities',
            'cids',
            'bed_types',
            'conditioned_rules',
            'details.description',
            'licenses',
            'license_groups',
            'network_components',
            'ocupations',
            'registers',
            'renasess',
            'services.service_classification',
            'sia_sihs',
            'tusses',
            'compatibles.register',
            'compatibles.compatible_procedure',
            'compatibles.compatible_register',
            'increments.license',
            'origin',
            'exception.restriction_procedure',
            'exception.compatible_procedure',
            'exception.register',
            'exception.compatible_register',
            )->where('competence_id',$competence)->limit(100)->get();
        return response()->json($procedures, 200);
    }
}
