<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Support\Facades\Request;
use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\InquiryInterface;
use Repositories\GeneralInterface;
use Repositories\BuildingInterface;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Building;
use App\Models\Project;
use App\Models\ProjectBuildingStatus;
use App\Models\BuildingFloor;
use App\Models\BuildingHasFloorPlans;
use App\Models\Flat;
use Validator,
    Input,
    Redirect,
    DB,
    Session,
    Response;

class AdminBuildingController extends Controller {

    protected $inquiryRepo;
    protected $generalRepo;

    public function __construct(InquiryInterface $inquiryRepo, GeneralInterface $generalRepo, BuildingInterface $buildingRepo) {
        $this->inquiryRepo = $inquiryRepo;
        $this->generalRepo = $generalRepo;
        $this->buildingRepo = $buildingRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $projectId=Input::get('project');
        $buildingName=Input::get('building');
        $buildings = $this->buildingRepo->getBuildings($projectId,$buildingName);
        if (count($buildings) == 0) {
            Session::flash('save', 'Sorry, no results found for your search.');
        } else {
            Session::flash('save', '');
        }
        return view('admin/Building/BuildingListingView', ['data' => $buildings]);
    }

    public function manageBuildings() {
        $buildingId = Request::segment(2);
        $building = Building::find($buildingId);
        $buildingFloorPlan = BuildingHasFloorPlans::where('building_id', $buildingId)->get();
        return view('admin/Building/addBuildingView', ['building' => $building, 'buildingFloorPlan' => $buildingFloorPlan]);
    }

    public function saveBuilding() {

        $rules = array(
            'project' => 'required',
            'name' => 'required',
            
                //'contact_no' => 'required|regex:/^[\d\-\+\s,]+$/',
        );
        $json_array['error'] = 'error';
        if (!Input::get('id'))
            $rules['name'] = 'required|unique:buildings,project_id' . Input::get('id');
        else {
            $rules['name'] = 'required|unique:buildings,project_id,' . Input::get('id');
        }

        $validator = Validator::make(Input::all(), $rules);
//        echo (Input::get('amenity'));
        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messaage = $validator->messages();
//            foreach ($rules as $key => $value) {
//                $json_array[$key . '_err'] = $messaage->first($key);
//            }
            return redirect('addBuilding')
                            ->withErrors($validator, 'Building');
        } else {
            DB::transaction(function() {
                if (!Input::get('id')) {
                    $save_building = new Building();
                    $save_building->created_by = Session::get('userId');
                    //$save_building->payment_slab = Input::get('payment_slab');
                } else {
                    $save_building = Building::find(Input::get('id'));
                    $save_building->updated_by = Session::get('userId');
                }
                $save_building->name = Input::get('name');
                $save_building->project_id = Input::get('project');
                $save_building->total_floor = Input::get('total_floor');
                $save_building->flat_per_floor = Input::get('flat_per_floor');
                $save_building->total_flats = Input::get('total_flats');
                $save_building->flat_starts_floor = Input::get('flat_starts_floor');
                $save_building->description = Input::get('description');
                $save_building->sq_feet_area = Input::get('sq_feet_area');
                $save_building->current_sq_feet_rate = Input::get('current_sq_feet_rate');
                $save_building->floor_rise = Input::get('floor_rise');
                $save_building->dev_charges = Input::get('dev_charges');
                $save_building->club_membership_charges = Input::get('club_membership_charges');
                $save_building->parking_charges = Input::get('parking_charges');
                $save_building->advance_maintenace = Input::get('advance_maintenace');
                $save_building->flat_type = Input::get('flat_type');
                $save_building->status = Input::get('status');
                $save_building->amenity = (Input::get('amenity')) ? json_encode(Input::get('amenity')) : '';

                if (!Input::get('id')) {
                    $save_building->save();
                    $building_id = $save_building->id;

                    for ($i = Input::get('flat_starts_floor'); $i <= Input::get('total_floor'); $i++) {
                        $floor_id = DB::table('building_floors')->insertGetId(
                                array('building_id' => $building_id, 'floor_no' => $i)
                        );

                        for ($j = 1; $j <= Input::get('flat_per_floor'); $j++) {
                            DB::table('flats')->insert(
                                    array(
                                        'building_id' => $building_id,
                                        'custom_flat_id' => $i . '0' . $j,
                                        'flat_area' => Input::get('sq_feet_area'),
                                        'current_sq_feet_rate' => Input::get('current_sq_feet_rate'),
                                        'floor_rise' => Input::get('floor_rise'),
                                        'flat_type' => Input::get('flat_type'),
                                        'floor_id' => $floor_id,
                                        'status' => 1,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'created_by' => Session::get('userId')
                                    )
                            );
                        }
                    }
//                    $this->update_slab_for_building($building_id);
                    $this->saveFloorPlan(Input::get('floor_plan_title'), Input::file('floor_plan'), $building_id);
                } else {
                    $success_update = $save_building->update();
                    $building_id = Input::get('id');
                    //   $this->update_slab_for_building($building_id);
                    $this->saveFloorPlan(Input::get('floor_plan_title'), Input::file('floor_plan'), $building_id);
                    //echo DB::table('flats')->where('building_id',$building_id)->count(); 
                    $cnt = DB::table('building_floors')->where('building_id',$building_id)->count() + Input::get('flat_starts_floor');
                    for ($i = $cnt; $i <= Input::get('total_floor'); $i++) {
                        echo '<br>'.$floor_id = DB::table('building_floors')->insertGetId(
                                array('building_id' => $building_id, 'floor_no' => $i)
                        );
                        for ($j = 1; $j <= Input::get('flat_per_floor'); $j++) { 
                            DB::table('flats')->insert(
                                    array(
                                        'building_id' => $building_id,
                                        'custom_flat_id' => $i . '0' . $j,
                                        'flat_area' => Input::get('sq_feet_area'),
                                        'current_sq_feet_rate' => Input::get('current_sq_feet_rate'),
                                        'floor_rise' => Input::get('floor_rise'),
                                        'flat_type' => Input::get('flat_type'),
                                        'floor_id' => $floor_id,
                                        'status' => 1,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'created_by' => Session::get('userId')
                                    )
                            );
                        }
                    }
                    //exit;
                }
            });
   
            $json_array['limit'] = Session::get('limit');
            Session::flash('save', 'Building details saved successfully.');
            $json_array['error'] = 'success';
        }
        //echo json_encode($json_array);
        return redirect()->route("buildings");
    }

    public function saveFloorPlan($FlPlanTitle, $floorPlanImages, $buildingId) {

        DB::table('building_has_floor_plans')->where('building_id', $buildingId)->delete();
        if ($FlPlanTitle) {
            for ($i = 0; $i < count($FlPlanTitle); $i++) {
                if ($FlPlanTitle[$i] && $FlPlanTitle[$i] != '') {
                    if ($floorPlanImages[$i] != '') {
                        $fileName = $buildingId . '_' . $floorPlanImages[$i]->getClientOriginalName();
                        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/public/admin/BuildingFloorPlans';
                        //chmod($filePath, 0777);
                        $floorPlanImages[$i]->move($filePath, $fileName);
                        DB::table('building_has_floor_plans')->insert(array(
                            'building_id' => $buildingId,
                            'title' => $FlPlanTitle[$i],
                            'image_path' => $filePath . '/' . $fileName,
                            'image_name' => $fileName
                        ));
                    }
                }
            }
        }
    }

    public function flatListing() {
        $buildingId = Request::segment(3);
        $flatNo = Input::get('flat_id');
        $floorNo = Input::get('floor');
        $flats = $this->buildingRepo->getFlatsByBuiding($buildingId, $flatNo, $floorNo);
        $flatCount = $this->buildingRepo->getFlatsByBuiding($buildingId, $flatNo, $floorNo, 'yes');
        $floors = $this->buildingRepo->getFloorByBuilding($buildingId);
        return view('admin/Building/manageFlatView', ['flats' => $flats, 'floors' => $floors, 'flatCount' => $flatCount]);
    }

    function saveFlat() {
        $fields = '';
        $rules_array = array();
        foreach (Input::all() as $k => $v) {
            $rules_array[] = $k;
        }
        
        $rules = $rules_array;
        $json_array['error'] = 'error';

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messaage = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '_err'] = $messaage->first($key);
            }
        } else {
            $data_fields = Input::all();
            foreach ($data_fields as $key => $value) {
                $temp = (explode('_', $key));
                $flat_id = end($temp);
                $column = trim(str_replace('_' . $flat_id, '', $key));
                $save_flat = Flat::find($flat_id);
                $save_flat->updated_by = Session::get('admin_user_id');
                $save_flat->$column = $value;
                $save_flat->update();
                Session::flash('save', 'Flat(s) saved successfully.');
                $json_array['error'] = 'success';
            }
        }
        echo json_encode($json_array);
    }

    function deleteFlat($flatId, $buildingId) {
        $count = DB::table('enquiries')->where('building_id',$buildingId)->where('flat_id',$flatId)->count();
        if($count == 0)
        {
            DB::table('flats')->where('id', $flatId)->where('building_id', $buildingId)->delete();
            Session::flash('save', "Flat deleted successfully.");
        }
        else
        {
            Session::flash('save', "This flat can't be deleted.");
        }
        if (is_numeric($flatId) && $flatId && $buildingId) {
            return Redirect::to('/building/flat/' . $buildingId);
        }
    }
    public function getBuildingFloorPlan()
    {
        $buildingId = Input::get('buildingId');
        $buildingFloorPlan = BuildingHasFloorPlans::where('building_id', $buildingId)->get();
        $html = ""; 
        if(count($buildingFloorPlan)>0)
        {
            $html.= '<br><label>Building Floor Plan:</label><br>';
            foreach ($buildingFloorPlan as $k=>$v)
            {
                $html.="<input type='checkbox' name='attachmentVal[]' value='".$v->image_path."'><span>".$v->image_name."</span><br>";
            }
            $html.='<br>';
        }
        echo $html;
    }

}
