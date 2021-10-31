<?php  

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertytypeController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\BlogCategoriesController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\LanguagesController;
use App\Http\Controllers\PropertytypesController;
use App\Http\Controllers\PropertyfeaturesController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\AgentpackagesController;
use App\Http\Controllers\PropertystatusController;
use App\Http\Controllers\AgentManagementController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmiratesController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ManageListingController;
use App\Http\Controllers\AllPropertyController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\QuotaUsageController;
use App\Http\Controllers\AllEmirateController;
use App\Http\Controllers\AgenciesAgentController;
use App\Http\Controllers\AgencyDataShowController;  
use App\Http\Controllers\WebsiteUserController;  
use App\Http\Controllers\UserAuthController;  
use App\Http\Controllers\MessageController;  
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\AgentManageListingController; 
use App\Http\Controllers\AgentDataShowController; 
use App\Http\Controllers\BlogController; 
use App\Http\Controllers\PageController; 
use App\Http\Controllers\OwnercontactController;  
use App\Http\Controllers\ShowBlogsController;  
use App\Http\Controllers\BlogsCategoriesController; 
use App\Http\Controllers\UploaderController; 
use App\Http\Controllers\TechnicalmessageController;   
// header("Access-Control-Allow-Origin"); 
// header('Access-Control-Allow-Methods', 'GET, POST, PUT,DELETE');
// header('Access-Control-Allow-Headers', 'Content-Type');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('register',[AuthController::class,'register']);
// Route::post('login',[AuthController::class,'login']);
// Route::post('logout', [AuthController::class,'logout']);
// Route::post('refresh', [AuthController::class,'refresh']);
// Route::post('me', [AuthController::class,'me']);

Route::group([

    'middleware' =>'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

});



// Route::fallback(function(){
//    // return respo
//     return response()->json([
//         'message' => 'Page Not Found. If error persists, contact info@website.com'], 404);
// });

//Route::post('login', [AuthController::class,'login']);
//add this middleware to ensure that every request is authenticated
Route::middleware('auth:api')->group(function(){
    Route::post('dashboard', [DashboardController::class,'index']);
});
// admin dashboard
Route::get('roles',[RoleController::class,'roles']);
Route::get('employees',[EmployeeController::class,'index']);
Route::post('employee',[EmployeeController::class,'store']);
Route::get('employees/{id}',[EmployeeController::class,'show']);
Route::get('edit-employee/{id}',[EmployeeController::class,'edit']);
Route::put('employee/{id}',[EmployeeController::class,'update']);
Route::get('cities',[EmiratesController::class,'index']);
// all modules admin side
Route::get('agency-agents',[AgenciesAgentController::class,'agents']);
Route::get('agency-properties',[AgenciesAgentController::class,'properties']);
Route::get('agency-agency',[AgenciesAgentController::class,'agency']);
Route::get('agency-agentdetails/{id}',[AgenciesAgentController::class,'agencyagentdetails']);
Route::get('countries',[CountryController::class,'countries']);
Route::resource('blogcategories',BlogCategoriesController::class);
Route::get('blogscategories',[BlogsCategoriesController::class,"category"]);
Route::group(['middleware' => ['api','jwt.verify']], function() {
Route::resource('categories',CategoriesController::class);
});
Route::group(['middleware' => ['api','jwt.verify']], function() {
Route::get('specialist',[SpecialistController::class,'index']);
});
Route::resource('features',FeatureController::class);
Route::resource('status',StatusController::class);
Route::resource('languages',LanguagesController::class);
Route::resource('propertytypes',PropertytypesController::class);
Route::get('allpropertytypes',[PropertytypesController::class,"allpropertytypes"]);
Route::get('propertytypeByCatId/{catid}',[PropertytypesController::class,'propertytypeByCatId']);
Route::get('agencies',[AgencyController::class,'index']);
Route::post('agencies',[AgencyController::class,'store']);
Route::get('edit-agency/{id}',[AgencyController::class,'edit']);
Route::get('show-agency/{id}',[AgencyController::class,'show']);
Route::put('agency/{id}',[AgencyController::class,'update']);
Route::delete('agency/{id}',[AgencyController::class,'destroy']);
Route::get('show-agent/{id}',[AgentController::class,'show']);
Route::get('agents',[AgentManagementController::class,'index']);
Route::post('agents',[AgentManagementController::class,'store']);
Route::get('agents/{id}',[AgentManagementController::class,'edit']);
Route::put('agents/{id}',[AgentManagementController::class,'update']);
Route::delete('agents/{id}',[AgentManagementController::class,'destroy']);
Route::resource('propertyfeatures',PropertyfeaturesController::class);
Route::resource('packages',PackagesController::class);
Route::resource('agentpackages',AgentpackagesController::class);
Route::resource('propertystatus',PropertystatusController::class);
// Route::resource('propertytype',PropertytypeController::class);
// R

Route::get('showagent/{id}',[AgentController::class,'showagent']);
// agencies dashboard
Route::get('managelisting',[ManageListingController::class,'index']);
Route::post('filtermanagelisting',[ManageListingController::class,'filtermanagelisting']);
Route::post('changestatus',[ManageListingController::class,'changestatus']);
Route::post('changepackage',[ManageListingController::class,'changepackage']);
Route::post('refresh',[ManageListingController::class,'refresh']);
Route::get('quota-usage',[QuotaUsageController::class,'index']);
Route::get('users',[AgencyDataShowController::class,'users']);
Route::post('brokeragents',[AgencyDataShowController::class,'agents']);
Route::get('filterLocation',[AgencyDataShowController::class,'locations']);
Route::get('agencyproperties',[AgencyDataShowController::class,'allproperties']);
Route::get('agencyagents',[AgencyDataShowController::class,'allagents']);
Route::get('agencyagentsproperties',[AgencyDataShowController::class,'agentproperties']);
Route::get('agencypackegedetails',[AgencyDataShowController::class,'packegedetails']);
Route::get('agencypackegedetailswithusage',[AgencyDataShowController::class,'packegedetailswithusage']);
Route::post('filterusagepackage',[AgencyDataShowController::class,'filterusagepackage']);
Route::get('agencypropertystatus',[AgencyDataShowController::class,'propertystatus']);
// agents
Route::get('properties',[PropertyController::class,'index']);
Route::post('properties',[PropertyController::class,'store']);
Route::post('propertiesuploads',[PropertyController::class,'upload']);
Route::get('properties/{id}',[PropertyController::class,'edit']);
Route::delete('deletemedia/{id}',[PropertyController::class,'deletemedia']);
Route::put('properties/{id}',[PropertyController::class,'update']);
Route::group(['middleware' => ['api','jwt.verify']], function() {
Route::get('agentmanagelisting',[AgentManageListingController::class,'index']);
Route::post('agentfiltermanagelisting',[AgentManageListingController::class,'filtermanagelisting']);
Route::get('agentfilterLocation',[AgentDataShowController::class,'locations']);
});
Route::post('uploader',[UploaderController::class,'upload']);
Route::get('downloader',[UploaderController::class,'download']);
// chat app
Route::get('users/{id}',[MessageController::class,'users']);
Route::get('allmessages/{id}',[MessageController::class,'index']);
Route::post('newmessage',[MessageController::class,'saveMessage']);
Route::post('ownerMessage',[OwnercontactController::class,'saveMessage']);
Route::post('sendallagents',[AgencyDataShowController::class,'sendallagents']);
// website 
Route::get('allproperties',[AllPropertyController::class,'index']);
Route::get('details/{id}',[AllPropertyController::class,'details']);
Route::get('show-property/{id}',[AllPropertyController::class,'show']);
Route::get('search-property/{data}',[AllPropertyController::class,'search']);
Route::get('autocomplete',[AllPropertyController::class,'autocomplete']);



// filter data from back end

//filter home page
Route::post('filterproperties',[AllPropertyController::class,'filterproperties']);
Route::post('filter',[AllPropertyController::class,'filter']);
Route::get('recentproperties',[AllPropertyController::class,'recent']);
Route::post('allfiles',[AllPropertyController::class,'allfiles']);
Route::get('test/{data}',[AllPropertyController::class,'test']);
Route::post('upload',[PropertyController::class,'upload']);
Route::get('emirates',[AllEmirateController::class,'index']);
Route::get('area/{emirate_name}',[AllEmirateController::class,'area']);
Route::get('streetorbuild_en/{streetorbuild_en}',[AllEmirateController::class,'streetorbuild_en']);
Route::get('locations',[AllPropertyController::class,'location']);
Route::get('getLocation/{switcher}/{data}',[AllPropertyController::class,'getlocation']);

Route::get('propertymap',[AllPropertyController::class,'propertymap']);


Route::get('agentinfo',[CountryController::class,'agentinfo']);


Route::get('locationandagent',[AgentController::class,'locationandagent']);
Route::post('filterAgent',[AgentController::class,'filteragent']);
Route::post('fetchAgentsByLocation',[AgentController::class,'fetchAgentsByLocation']);

Route::group(['middleware' => ['api','jwt.verify']], function() {
Route::post('wishlist',[WebsiteUserController::class,'wishlist']);
Route::get('allwishlist',[WebsiteUserController::class,'allwishlist']);
});



Route::group([

    'middleware' =>'api'

], function ($router) {

    Route::post('login', [UserAuthController::class,'login']);
    Route::post('register', [UserAuthController::class,'register']);
    
   

});

Route::group(['middleware' => ['api','jwt.verify']], function() {
    Route::post('updateUser', [UserAuthController::class,'updateUser']);
    Route::post('logout', [UserAuthController::class,'logout']);
    Route::post('refresh', [UserAuthController::class,'refresh']);
    Route::post('me', [UserAuthController::class,'me']);
});

Route::get('chatusers',[UserAuthController::class,'chatUsers']);





Route::group(['middleware' => ['api','jwt.verify']], function() {
    Route::post('changePassword', [ProfileController::class,'changePassword']);
    Route::post('changePicture', [ProfileController::class,'changePicture']);
    });

    Route::group(['middleware' => ['api','jwt.verify']], function() {
        Route::get('blogs', [BlogController::class,'index']);
        Route::post('newblog', [BlogController::class,'store']);
        Route::get('blog/{id}', [BlogController::class,'edit']);
        Route::put('blog/{id}', [BlogController::class,'update']);
        Route::delete('blog/{id}', [BlogController::class,'delete']);
        });


Route::group(['middleware' => ['api','jwt.verify']], function() {
   Route::get('pages', [PageController::class,'index']);
   Route::post('newpage', [PageController::class,'store']);
   Route::get('page/{id}', [PageController::class,'edit']);
   Route::put('page/{id}', [PageController::class,'update']);
   Route::delete('page/{id}', [PageController::class,'delete']);
});


Route::post('allblogs', [ShowBlogsController::class,'index']);
Route::get('single-blog/{id}', [ShowBlogsController::class,'blogById']);


Route::post('technicalmessage',[TechnicalmessageController::class,'sendmessage']);