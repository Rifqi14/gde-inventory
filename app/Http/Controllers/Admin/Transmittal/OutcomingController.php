<?php

namespace App\Http\Controllers\Admin\Transmittal;

use App\Exceptions\DocumentExternal\Properties\PropertiesException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transmittal\Outcoming\OutcomingRequest;
use App\Models\DocExternal\DocumentExternal;
use App\Models\DocExternal\DocumentExternalRevision;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Transmittal\Outcoming\OutcomingTransmittal;
use App\Models\Transmittal\TransmittalProperties\CategoryContractor;
use App\Models\Transmittal\TransmittalProperties\TransmittalOrganizationCode;
use App\Traits\InteractWithApiResponse;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PDF;

class OutcomingController extends Controller
{
    use InteractWithApiResponse;
    function __construct() {
        $menu   = Menu::with(['parentMenu'])->GetByRoute('outcoming')->first();
        view()->share('menu_name', $menu->menu_name);
        view()->share('parent_name', $menu->parentmenu->menu_name);
        view()->share('menu_active', url("admin/outcoming"));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (in_array('read', $request->actionmenu)) {
            return view('admin.transmittal.outcoming.index');
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.transmittal.outcoming.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OutcomingRequest $request)
    {
        return DB::transaction(function() use ($request) {
            try {
                $data   = [
                    'transmittal_date'      => dbDate(request('transmittal_date')),
                    'gde_contract_no'       => request('gde_contract_no'),
                    'gde_contract_title'    => request('gde_contract_title'),
                    'transmittal_title'     => request('transmittal_title'),
                    'transmittal_remark'    => request('transmittal_remark'),
                    'sender'                => request('sender_id'),
                    'contractor_group_id'   => request('contractor_group_id'),
                    'sender_address'        => request('sender_address'),
                    'recipient_address'     => request('recipient_address'),
                    'issued_by'             => auth()->user()->id,
                    'status'                => request('status'),
                    'tab'                   => request('tab'),
                    'sender_alias'          => request('sender_alias'),
                    'recipient_alias'       => request('recipient_alias'),
                ];
                $outcoming = OutcomingTransmittal::create($data);
                $outcoming->transmittal_no = $this->createTransmittalNo($outcoming);
                $outcoming->save();
                $outcoming->attentions()->sync(request('attention_id'));
                $outcoming->ccs()->sync(request('cc'));
                $outcoming->documents()->sync(request('revision_id'));
                $this->storeFile($request, $outcoming->id);
            } catch (\Illuminate\Database\QueryException $ex) {
                throw new PropertiesException("Error create data: {$ex->errorInfo[2]}", Response::HTTP_BAD_REQUEST);
            }
            return $this->success(Response::HTTP_OK, "Success Create Data", ['tab' => request('tab')], route('outcoming.index'));
        });
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
        $data = OutcomingTransmittal::with(['contractorgroup', 'issuedby', 'senderuploader', 'recipientuploader', 'attentions', 'ccs', 'documents.document', 'documents.sheetsize', 'documents.workflow'])->find(request()->id);
        if ($data) {
            return view('admin.transmittal.outcoming.edit', compact('data'));
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function createTransmittalNo($outcoming)
    {
        $temp_transmittal   = explode("-", $outcoming->temp_transmittal_no);
        return "$temp_transmittal[0]-$temp_transmittal[1]-$temp_transmittal[2]-$outcoming->sender_alias-$outcoming->recipient_alias-$temp_transmittal[3]";
    }

    public function storeFile(OutcomingRequest $request, $id)
    {
        return DB::transaction(function() use ($request, $id) {
            $transmittal    = OutcomingTransmittal::find($id);
            if (!$transmittal) {
                throw new PropertiesException("Transmittal not found", Response::HTTP_BAD_REQUEST);
            }
            $data   = [];
            $src        = "assets/transmittal/outgoing/" . date('Y') . "/" . date('m') . "/$request->transmittal_no";
            if (request()->file('sender_signed_copy')) {
                $filename   = "$request->sender_signed_copy_name.{$request->file('sender_signed_copy')->getClientOriginalExtension()}";
                $sources    = "$src/Sender Signed Copy";

                if (!file_exists($sources)) {
                    mkdir($sources, 0777, true);
                }
                $move       = $request->file('sender_signed_copy')->move($sources, $filename);
                if (!$move) {
                    throw new PropertiesException("Error upload document", Response::HTTP_BAD_REQUEST);
                }
                $transmittal->sender_file_name  = $request->sender_signed_copy_name;
                $transmittal->sender_signed_copy= "$src/$filename";
                $transmittal->sender_signed_copy_uploaded_by = auth()->user()->id;
            }
            if (request()->file('recipient_signed_copy')) {
                $filename   = "$request->recipient_signed_copy_name.{$request->file('sender_signed_copy')->getClientOriginalExtension()}";
                $sources    = "$src/Recipient Signed Copy";

                if (!file_exists($sources)) {
                    mkdir($sources, 0777, true);
                }
                $move       = $request->file('recipient_signed_copy')->move($sources, $filename);
                if (!$move) {
                    throw new PropertiesException("Error upload document", Response::HTTP_BAD_REQUEST);
                }
                $transmittal->recipient_file_name  = $request->recipient_signed_copy_name;
                $transmittal->recipient_signed_copy= "$src/$filename";
                $transmittal->recipient_signed_copy_uploaded_by = auth()->user()->id;
            }
            $transmittal->save();

            return true;
        });
    }

    public function defaultData(Request $request)
    {
        $outcoming = OutcomingTransmittal::find($request->outcoming_id);
        $data   = $this->findSenderFromUserLogin($outcoming ? $outcoming->issued_by : $request->id);
        // $data   = $this->findSenderFromUserLogin(101);
        $organizationCode   = $this->findOrganizationCode($outcoming ? $outcoming->tab : $request->tab);
        $contractor         = $this->findCategoryContractor($request->contractor_group_id);
        $sender_address     = $data->ownership ? config('configs.company_address') : null;
        $recipient_address  = $data->ownership ? null : config('configs.company_address');
        $recipient_alias    = $data->ownership ? null : $data->code;
        $sender_alias       = $data->ownership ? $data->code : null;
        if ($contractor) {
            $sender_address     = $data->ownership ? config('configs.company_address') : $contractor->address;
            $recipient_address  = $data->ownership ? $contractor->address : config('configs.company_address');
            $recipient_alias   = $data->ownership ? $contractor->code : $data->code;
            $sender_alias= $data->ownership ? $data->code : $contractor->code;
        }
        
        return response()->json([
            'transmittal_date'      => $outcoming ? date('d/m/Y', strtotime($outcoming->transmittal_date)) : date('d/m/Y'),
            'sender'                => $data->ownership ? $organizationCode->name : $data->name,
            'sender_address'        => $sender_address,
            'sender_alias'          => $sender_alias,
            'recipient_alias'       => $recipient_alias,
            'recipient_address'     => $recipient_address,
            'ownership'             => $data->ownership,
            'issued_by'             => $outcoming ? $outcoming->issuedby->name : auth()->user()->name,
        ], Response::HTTP_OK);
    }

    public function findOrganizationCode($code)
    {
        $data   = TransmittalOrganizationCode::where('code', $code)->first();
        return $data;
    }

    public function findCategoryContractor($id)
    {
        $data   = CategoryContractor::whereHas('groups', function(\Illuminate\Database\Eloquent\Builder $q) use ($id) {
            $q->where('role_id', $id);
        })->first();
        return $data;
    }

    public function findSenderFromUserLogin($id)
    {
        $data   = CategoryContractor::whereHas('groups', function(\Illuminate\Database\Eloquent\Builder $q) use ($id) {
            $q->whereHas('group', function(\Illuminate\Database\Eloquent\Builder $que) use ($id) {
                $que->whereHas('users', function(Builder $query) use ($id) {
                    $query->where('user_id', $id);
                });
            });
        })->first();
        return (object) $data;
    }

    public function selectAttention(Request $request)
    {
        $name       = strtoupper($request->name);
        $loginUser  = User::with(['roles'])->find(auth()->user()->id);
        $category   = $this->findCategoryContractor($loginUser->roles()->first()->id);
        $contractor = $this->findCategoryContractor($request->category_contractor_id);
        $organization = $this->findOrganizationCode($request->tab);

        // Count Data
        $query      = User::with(['roles'])->whereRaw("upper(name) like '%$name%'");
        if ($category->ownership) {
            $query->whereHas('roles.categoryContractors.contractor', function(Builder $q) use ($contractor) {
                $q->where('id', $contractor->id);
            });
        } else {
            $query->whereHas('roles.organizationCodes', function(Builder $q) use ($organization) {
                $q->where('transmittal_organization_code_id', $organization->id);
            });
        }
        return response()->json([
            'total'     => $query->get()->count(),
            'rows'      => $query->get(),
        ]);
    }

    public function selectContractor(Request $request)
    {
        $name = strtoupper($request->name);
        $loginUser  = User::with(['roles'])->find(auth()->user()->id);
        // $loginUser  = User::with(['roles'])->find(101);
        $category   = $this->findCategoryContractor($loginUser->roles()->first()->id);

        // Count Data
        $query      = Role::whereRaw("upper(name) like '%$name%'");
        if (!$category->ownership) {
            $query->whereHas('categoryContractors.contractor', function(Builder $q) use ($category) {
                $q->where('id', $category->id);
                $q->orderBy('name', 'asc');
            });
        }
        $query->whereHas('categoryContractors.contractor', function(Builder $q) use ($category) {
            $q->Where('ownership', false);
            $q->orderBy('name', 'asc');
        });
        return response()->json([
            'total'     => $query->get()->count(),
            'rows'      => $query->get(),   
        ]);
    }

    public function selectCC(Request $request)
    {
        $name       = strtoupper($request->name);
        $user       = User::with(['roles'])->find(auth()->user()->id)->roles()->first()->id;
        $category   = $this->findCategoryContractor($user);
        // dd($category);

        // Count Data
        $query = Role::whereRaw("upper(name) like '%$name%'");
        // dd($query->first());
        if (!$category->ownership) {
            $query->whereHas('categoryContractors.contractor', function (Builder $q) use ($category) {
                $q->where('id', $category->id);
                $q->where('ownership', true);
            });
        }
        $query->whereHas('categoryContractors.contractor', function (Builder $q) use ($category) {
        });
        return response()->json([
            'total' => $query->get()->count(),
            'rows'  => $query->get(),
        ]);
    }

    public function selectRevision(Request $request)
    {
        $name   = strtoupper($request->name);
        $loginUser = User::with(['roles'])->find(auth()->user()->id);
        $contractor = $this->findCategoryContractor($loginUser->roles()->first()->id);
        // $contractor = $this->findCategoryContractor(6);

        // Get Data
        $query  = DocumentExternal::with(['latestRevisionOwnership.sheetsize', 'latestRevisionOwnership.workflow', 'latestRevision.sheetsize', 'latestRevision.workflow'])->whereRaw("upper(document_number) like '%$name%'")->get();
        $data   = [];
        foreach ($query as $key => $value) {
            if ($contractor->ownership) {
                $value->revision = $value->latestRevisionOwnership;
            } else {
                $value->revision = $value->latestRevision;
            }
            $data[] = $value;
        }
        return response()->json([
            'total'     => $query->count(),
            'rows'      => $data,
        ]);
    }

    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $tab            = $request->tab;
        $transmittal_no = strtoupper($request->transmittal_number);
        $transmittal_title  = strtoupper($request->transmittal_title);
        $attention_id   = $request->attention_id;

        // Query Data
        $queryData      = OutcomingTransmittal::with(['attentions'])->when($attention_id, function(Builder $q) {
            $q->whereHas('attentions', function(Builder $q) {
                $q->where('user_id', request('attention_id'));
            });
        })->when($transmittal_no, function(Builder $q) use ($transmittal_no) {
            $q->whereRaw("upper(transmittal_no) like '%$transmittal_no%'");
        })->whereRaw("upper(transmittal_title) like '%$transmittal_title%'")->where('issued_by', auth()->user()->id)->where('tab', $tab)->offset($start)->limit($length)->orderBy($sort, $dir);

        $data   = [];
        foreach ($queryData->get() as $key => $value) {
            $value->no  = ++$start;
            $data[]     = $value;
        }

        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $queryData->get()->count(),
            'recordsFiltered'   => $queryData->get()->count(),
            'data'              => $data,
        ], 200);
    }

    public function destroyDocument(Request $request)
    {
        $outcoming = OutcomingTransmittal::find($request->id);
        if (!$outcoming) {
            throw new PropertiesException('Outcoming data not found for this document');
        }
        $outcoming->documents()->detach($request->document_id);

        return $this->success(Response::HTTP_OK, "Delete document success", null, null);
    }

    public function pdfview($id)
    {
        $outcoming = OutcomingTransmittal::find($id);
        if ($id) {
            // $pdf = PDF::loadview('admin.pdf.outcoming.outcomingpdf', ['outcoming' => $outcoming]);
            // return $pdf->download('laporan-pdf.pdf');
        }
        return view('admin.pdf.outcoming.outcomingpdf', compact('outcoming'));
    }
}
