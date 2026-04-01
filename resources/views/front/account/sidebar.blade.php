<div class="card border-0 shadow mb-4 p-3">
                    <div class="s-body text-center mt-3">
                        @if (Auth::user()->profile_picture !='')
                            <img id="profile-image" src="{{ asset('profile_pictures/thumb/' . Auth::user()->profile_picture) }}" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        @else
                            <img id="profile-image" src="{{ asset('assets/images/avatar7.png') }}" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        @endif

                        <h5 id="user-name" class="mt-3 pb-0">{{ Auth::user()->name }}</h5>
                        <p id="user-designation" class="text-muted mb-1 fs-6">{{ Auth::user()->designation }}</p>
                        <div class="d-flex justify-content-center mb-2">
                            <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="btn btn-primary">Change Profile Picture</button>
                        </div>
                    </div>
                </div>
                <div class="card account-nav border-0 shadow mb-4 mb-lg-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush ">
                            <li class="list-group-item d-flex justify-content-between p-3">
                                <a href="#">Account Settings</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="{{ route('account.createJob') }}">Post a Job</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="{{ route('account.myJobs') }}">My Jobs</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="{{ route('account.myJobApplications') }}">Jobs Applied</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="{{ route('account.myJobs') }}">Saved Jobs</a>
                            </li> 
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <form action="{{ route('account.logout') }}" method="POST">
                                  @csrf
                                    <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold">
                                        Logout
                                    </button>
                                </form>
                            </li>                                                       
                        </ul>
                    </div>
                </div>