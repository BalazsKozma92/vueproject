<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vehicle::orderBy('created_at', 'ASC')->get();
    }

    public function getYaml() {
        if (!file_exists(base_path().'/storage/app/public/construct/save/vehicles.yml')) {
            mkdir(base_path().'/storage/app/public/construct/save', 0777, true);
            fopen(base_path().'/storage/app/public/construct/save/vehicles.yml', 'w');
        }
        
        if (!file_exists(base_path().'/storage/app/public/construct/save/vehicles_temp.yml')) {
            fopen(base_path().'/storage/app/public/construct/save/vehicles_temp.yml', 'w');
        }

        $yamlContents = Yaml::parse(file_get_contents(base_path().'/storage/app/public/construct/save/vehicles.yml'));

        $yaml = Yaml::dump($yamlContents);
        file_put_contents(base_path().'/storage/app/public/construct/save/vehicles_temp.yml', $yaml);

        return $yamlContents;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newVehicle = new Vehicle();
        $newVehicle->name = $request->vehicle['name'];
        $newVehicle->save();
        
        VehicleController::addNewEntryToVehicleYml($newVehicle->id - 1);

        return "A jármű elkészült.";
    }

    private function addNewEntryToVehicleYml($id) {
        if (file_exists(base_path().'/storage/app/public/construct/save/vehicles.yml')) {
            $yamlContentsMain = Yaml::parse(file_get_contents(base_path().'/storage/app/public/construct/save/vehicles.yml'));
        }

        $yamlContentsMain[$id]['spare_tyres'] = "";
        $yamlContentsMain[$id]['other_values'][0]['tyres'] = 2;
        $yamlContentsMain[$id]['other_values'][0]['hajtott'] = false;
        $yamlContentsMain[$id]['other_values'][0]['kormanyzott'] = false;

        $yaml = Yaml::dump($yamlContentsMain);
        file_put_contents(base_path().'/storage/app/public/construct/save/vehicles.yml', $yaml);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (file_exists(base_path().'/storage/app/public/construct/save/vehicles.yml')) {
            $yamlContentsMain = Yaml::parse(file_get_contents(base_path().'/storage/app/public/construct/save/vehicles.yml'));
        }

        $existingVehicle = Vehicle::find($id + 1);
        if ($existingVehicle) {
            $existingVehicle['name'] = $request->vehicleName;
            $existingVehicle->save();

            $updatedYaml['spare_tyres'] = $request->spareTyre;
            for ($i=0; $i < count($request->yamlContents['other_values']); $i++) { 
                $updatedYaml['other_values'][$i]['tyres'] = $request->yamlContents['other_values'][$i]['tyres'];
                $updatedYaml['other_values'][$i]['hajtott'] = $request->yamlContents['other_values'][$i]['hajtott'];
                $updatedYaml['other_values'][$i]['kormanyzott'] = $request->yamlContents['other_values'][$i]['kormanyzott'];
                if ($i != 0) {
                    $updatedYaml['other_values'][$i]['boogie'] = $request->yamlContents['other_values'][$i]['boogie'];
                }
            }
            $yamlContentsMain[$id] = $updatedYaml;
            $yaml = Yaml::dump($yamlContentsMain);
        
            file_put_contents(base_path().'/storage/app/public/construct/save/vehicles.yml', $yaml);

            return "A jármű frissítve lett.";
        }
        return "A jármű nem található.";
    }

    public function addAxle(Request $request, $id) {
        if (file_exists(base_path().'/storage/app/public/construct/save/vehicles_temp.yml')) {
            $yamlContentsTemp = Yaml::parse(file_get_contents(base_path().'/storage/app/public/construct/save/vehicles_temp.yml'));
        }

        $yamlContents = $request->yamlTemp;

        $nextId = count($yamlContents['other_values']);      
        $yamlContents['other_values'][$nextId]['tyres'] = 2;
        $yamlContents['other_values'][$nextId]['hajtott'] = false;
        $yamlContents['other_values'][$nextId]['kormanyzott'] = false;
        $yamlContents['other_values'][$nextId]['boogie'] = false;
        
        $yamlContentsTemp[$id] = $yamlContents;
        $yaml = Yaml::dump($yamlContentsTemp);
    
        file_put_contents(base_path().'/storage/app/public/construct/save/vehicles_temp.yml', $yaml);

        return $yamlContentsTemp;
    }

    public function removeAxle(Request $request, $id) {
        if (file_exists(base_path().'/storage/app/public/construct/save/vehicles_temp.yml')) {
            $yamlContentsTemp = Yaml::parse(file_get_contents(base_path().'/storage/app/public/construct/save/vehicles_temp.yml'));
        }

        $yamlContents = $request->yamlTemp;

        $newYamlContents = [];

        $previousId = count($yamlContents['other_values']) - 1;      

        for ($i=0; $i < $previousId; $i++) { 
            $newYamlContents[$i]['tyres'] = $yamlContents['other_values'][$i]['tyres'];
            $newYamlContents[$i]['hajtott'] = $yamlContents['other_values'][$i]['hajtott'];
            $newYamlContents[$i]['kormanyzott'] = $yamlContents['other_values'][$i]['kormanyzott'];
            if ($i != 0) {
                $newYamlContents[$i]['boogie'] = $yamlContents['other_values'][$i]['boogie'];
            }
        }
        $yamlContentsTemp[$id]['other_values'] = $newYamlContents;

        $yaml = Yaml::dump($yamlContentsTemp);
    
        file_put_contents(base_path().'/storage/app/public/construct/save/vehicles_temp.yml', $yaml);

        return $yamlContentsTemp;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingVehicle = Vehicle::find($id);
        if ($existingVehicle) {
            $existingVehicle->delete();

            if (file_exists(base_path().'/storage/app/public/construct/save/vehicles.yml')) {
                $yamlContentsMain = Yaml::parse(file_get_contents(base_path().'/storage/app/public/construct/save/vehicles.yml'));
            }
    
            $newYaml = [];
            foreach ($yamlContentsMain as $mainKey => $value) {
                if ($mainKey == $id - 1) {
                    continue;
                }
                $newYaml[$mainKey]['spare_tyres'] = $yamlContentsMain[$mainKey]['spare_tyres'];
                foreach ($yamlContentsMain[$mainKey]['other_values'] as $secondaryKey => $value) {
                    $newYaml[$mainKey]['other_values'][$secondaryKey] = $value;   
                }
            }
    
            $yaml = Yaml::dump($newYaml);
            file_put_contents(base_path().'/storage/app/public/construct/save/vehicles.yml', $yaml);

            return "A járművet sikeresen törölted.";
        }
        return "A jármű nem található.";
    }
}
