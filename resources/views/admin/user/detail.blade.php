@extends('admin.layouts.app')
@section('title', 'View User')
@section('stylesheets')
<style>
    .profile-user-img {
        width: 100px;
        height: 100px;
        border-color: #fdfdfd;
        box-shadow: 0px 0px 11px -4px rgb(0 0 0 / 48%);
    }
    .profile-username {
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
    }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
	<div class="col-sm-4">
		<!-- <h5 class="m-0 ml-2 text-dark text-md breadcrumb">Grievance Redress &nbsp;<small class="font-uppercase"></small></h5> -->
		<h1 id="title-branch" class="m-0 text-dark">
			View User
		</h1>
	</div>
	<div class="col-sm-8">
		<ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
			<li class="breadcrumb-item">Preferences</li>
			<li class="breadcrumb-item">User</li>
			<li class="breadcrumb-item active">View</li>
		</ol>
	</div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">Detail User</h5>
                        </span>
                    </div>
                </div>
                <div class="box box-primary mb-3">
                    <div class="box-body box-profile">
                        <div class="" style="height: 100px;">
                            <img class="profile-user-img img-responsive img-circle float-left mr-3" src="https://healthmeterkp.biiscorp.com/adminlte/images/user2-160x160.jpg" alt="User profile picture">
                            <h3 class="profile-username">
                                <b>{{$user->username}}</b><br>
                                <small class="text-muted text-center d-inline font-italic">{{ $user->group_description }}</small>
                            </h3>
                        </div>
                        <div class="clearfix mb-3"></div>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{$user->email}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Username</b> <a class="float-right">{{$user->name}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> <a class="float-right">{{$user->is_active}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Supervisor</b> <a class="float-right">{{$user->spv_name}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Site</b> <a class="float-right">{{$user->site_name}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Created at</b> <a class="float-right">{{$user->created_at}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Last Login</b> <a class="float-right">{{$user->created_at}}</a>
                            </li>
                        </ul>
                        {{-- <a href="{{route('user.index')}}" class="btn btn-default btn-block"><b>Back</b></a> --}}
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <span class="title mb-5">
                            <hr />
                            <h5 class="text-md text-dark text-bold">Other</h5>
                        </span>
                        {{-- <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs tabs-engineering">
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Access Unit</a></li>
                                <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Log Login</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    asd
                                </div>
                                <div class="tab-pane" id="timeline">
                                    ads
                                </div>
                            </div>
                        </div> --}}
                        <table class="table datatable" id="">   
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="100">IP Address</th>
                                    <th>Device</th>
                                    <th width="100">Last Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr role="row" class="odd">
                                    <td class="text-right dtr-control">1</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36</td>
                                    <td class="text-center sorting_1">2021-01-17 23:51:46</td>
                                </tr>
                                <tr role="row" class="even">
                                    <td class="text-right dtr-control">2</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36</td>
                                    <td class="text-center sorting_1">2021-01-18 09:03:20</td>
                                </tr>
                                <tr role="row" class="odd">
                                    <td class="text-right dtr-control">3</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36</td>
                                    <td class="text-center sorting_1">2021-01-18 09:39:53</td>
                                </tr>
                                <tr role="row" class="even">
                                    <td class="text-right dtr-control">4</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36</td>
                                    <td class="text-center sorting_1">2021-01-18 15:26:31</td>
                                </tr>
                                <tr role="row" class="odd">
                                    <td class="text-right dtr-control">5</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36</td>
                                    <td class="text-center sorting_1">2021-01-18 21:10:27</td>
                                </tr>
                                <tr role="row" class="even">
                                    <td class="text-right dtr-control">6</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36</td>
                                    <td class="text-center sorting_1">2021-01-19 09:11:23</td>
                                </tr>
                                <tr role="row" class="odd">
                                    <td class="text-right dtr-control">7</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:84.0) Gecko/20100101 Firefox/84.0</td>
                                    <td class="text-center sorting_1">2021-01-19 19:51:53</td>
                                </tr>
                                <tr role="row" class="even">
                                    <td class="text-right dtr-control">8</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:84.0) Gecko/20100101 Firefox/84.0</td>
                                    <td class="text-center sorting_1">2021-01-20 08:07:19</td>
                                </tr>
                                <tr role="row" class="odd">
                                    <td class="text-right dtr-control">9</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:84.0) Gecko/20100101 Firefox/84.0</td>
                                    <td class="text-center sorting_1">2021-01-20 18:13:37</td>
                                </tr>
                                <tr role="row" class="even">
                                    <td class="text-right dtr-control">10</td>
                                    <td>127.0.0.1</td>
                                    <td>Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:84.0) Gecko/20100101 Firefox/84.0</td>
                                    <td class="text-center sorting_1">2021-01-21 09:56:05</td>
                                </tr>
                            </tbody>
                        </table>
                        {{-- <h2 class="page-header">AdminLTE Custom Tabs</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Custom Tabs -->
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab_1" data-toggle="tab">Tab 1</a></li>
                                        <li><a href="#tab_2" data-toggle="tab">Tab 2</a></li>
                                        <li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">
                                            <b>How to use:</b>
                                            <p>Exactly like the original bootstrap tabs except you should use
                                                the custom wrapper <code>.nav-tabs-custom</code> to achieve this style.
                                            </p>
                                            A wonderful serenity has taken possession of my entire soul,
                                            like these sweet mornings of spring which I enjoy with my whole heart.
                                            I am alone, and feel the charm of existence in this spot,
                                            which was created for the bliss of souls like mine. I am so happy,
                                            my dear friend, so absorbed in the exquisite sense of mere tranquil existence,
                                            that I neglect my talents. I should be incapable of drawing a single stroke
                                            at the present moment; and yet I feel that I never was a greater artist than now.
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="tab_2">
                                            The European languages are members of the same family. Their separate existence is a myth.
                                            For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                                            in their grammar, their pronunciation and their most common words. Everyone realizes why a
                                            new common language would be desirable: one could refuse to pay expensive translators. To
                                            achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                                            words. If several languages coalesce, the grammar of the resulting language is more simple
                                            and regular than that of the individual languages.
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="tab_3">
                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                                            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                            It has survived not only five centuries, but also the leap into electronic typesetting,
                                            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                                            sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                                            like Aldus PageMaker including versions of Lorem Ipsum.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <!-- /.row -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(function(){
        $('.datatable').DataTable({
            language: {
                processing: `<div class="p-2 text-center">
                                <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
                            </div>`
            },
            aaSorting: [],
            filter: false,
            responsive: true,
            lengthChange: false,
            order: [[ 3, "asc" ]]
        });
    });
</script>
@endsection