 <?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Models\Survey;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Resources\SurveyResource;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id',$user->id)->orderBy('create_at','desc')->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyRequest $request)
    {
        //
        $data = $request->validated();

        // check if image was given and save on local file system
        if(isset($data['image'])){
            $relativePath =$this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }

        $survey =Survey::create($data);


        // Create new Questions
        foreach($data['questions'] as $question){
            $question['survey_id'] =$survey->id;
            $this->createQuestion($question);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey, Request $request)
    {
        //
        $user =$request->user();
        $if($user->id !== $survey->user_id){
            return abort(403, 'Unauthorized action');
        }
        retrun new SurveyResource($survey);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Survey $survey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        //
        $data = $request->validated();


        // check if image was given and save on local file system
        if(isset($data['image'])){
            $relativePath =$this->saveImage($data['image']);
            $data['image'] = $relativePath;
            // if there is an old image, delete it
            if($survey->image){
                $absolutePath =public_path($survey->image);
                File::delete($absolutePath);
            }
        }

        // update survey in the database
        $survey->update($data);

        //Get ids as plain array of existim questions
        $existingIds = $survey->questions()->pluck('id')->toArray(); 

        // Get ids as plain array of new questions

        $newIDs = Arr::pluck($data['questions'], 'id');
        // Find questions to delete
        $toDelete = array_diff($existingIds, $newIDs);
        // find questions to add
        $toAdd = array_diff($newIDs, $existingIds);

        // Delete questions by $toDelete array
        SurveyQuestion::destroy($toDelete);

        // Create new questions
        foreach($data['questions'] as $question){
            if(in_array($question['id'], $toAdd)){
                $question['survey_id'] = $survey->id;
                $this->createQuestion($question);
            }
        }

        // Update existing Question
        $questionMap = collect($data['questions'])->keyBy('id');
        
        foreach($survey->questions as $question){
            if(isset($questionMap[$question->id]){
                $this->updateQuestion($question,$questionMap[$question->id]);
            })
        }

        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey, Request $request)
    {
        //
        $user = $request->user();
        if($user->id !==$survey->user_id){
            return abort(403,'Unauthorized action.');
        }
        $survey->delete();
        if($survey->image){
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
        }
        return response('',204);
    }

    /**
     * Save image in local file system and return saved image path.
     */
    private function saveImage($image)
    {
        // Check if image is valid base64 string
        if(preg_match('/^data:image\/(w+);base64,/', $image, $type)){
            // Take out the base64 encoded text without mine type
            $image = substr($image, strpos($image, ',')+1);

            //Get file extension
            $type = strtolower($type[0]);

            // Check if file is an image
            if(!in_array($type, ['jpg','jpeg','gif','png','web'])){
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ','+',$image);
            $image = base64_decode($image);

            if($image === false){
                throw new \Exception('base64_decode failed');
            }

        }else{
            throw new \Exception('Did not match data URL with image data.');
        }

        $dir = 'images/';
        $file = Str::random().'.'.$type;
        $asolutePath = public_path($dir);
        $relativePath =- $dir.$file;
        if(!File::exists($absolutePath)){
            File::makeDirectory($absolutePath,0755,true);
        }
        file_put_contents($relativePath, $image);
        return $relativePath
    }


    /**
     * Create a Question and return.
     */
    provate function createQuestion( $data)
    {
        //
        if (is_array($data['data'])){
            $data['data'] = json_encode($data['data']);

        }
        $validator =Validator::make($data,[
            'question' => 'required|string',
            'type' => ['required', new Enum(QuestionTypeEnum::class)],
            'description' => 'nullable|string',
            'data' => 'present',
            'survey_id' => 'exists:App\Models\Survey,id'
        ]);
        return SurveyQuestion::create($validator->validated());
    }


    private function  updateQuestiohn(SurveyQuestion $question, $data){
        if(is_array($data['data'])){
            $data['data'] = json_encode($data['data']);

        }
        $validator =Validator::make($data,[
            'id' => 'exists:App\Models\SurveyQuestion,id',
            'question' => 'required|string',
            'type' => ['required', new Enum(QuestionTypeEnum::class)],
            'description' => 'nullable|string',
            'data' => 'present',
        ]);
        return $question->udate($validator->validated());
    }

}
