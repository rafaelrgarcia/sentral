<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=APP_NAME?></title>

    <!--JQUERY-->
    <script src="<?=PUBLIC_PATH?>js/jquery/3.3.1/jquery-3.3.1.min.js"></script>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="<?=PUBLIC_PATH?>css/bootstrap/4.1.1/bootstrap.min.css">
    <script src="<?=PUBLIC_PATH?>js/bootstrap/4.1.1/bootstrap.min.js"></script>

    <!-- DATATABLE -->
    <link rel="stylesheet" href="<?=PUBLIC_PATH?>assets/DataTables-1.10.21/datatables.min.css">
    <script src="<?=PUBLIC_PATH?>assets/DataTables-1.10.21/datatables.min.js"></script>

    <!-- jQuery validation -->
    <script src="<?=PUBLIC_PATH?>assets/jquery-validation-1.19.2/jquery.validate.min.js"></script>
    <script src="<?=PUBLIC_PATH?>assets/jquery-validation-1.19.2/additional-methods.min.js"></script>

    <!-- datetimepicker/moment -->
    <script src="<?=PUBLIC_PATH?>js/moment.min.js"></script>
    <script src="<?=PUBLIC_PATH?>assets/datetimepicker/js/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="<?=PUBLIC_PATH?>assets/datetimepicker/css/jquery.datetimepicker.css">

    <!-- jQuery select2 -->
    <script src="<?=PUBLIC_PATH?>assets/select2-4.0.13/js/select2.full.min.js"></script>
    <link rel="stylesheet" href="<?=PUBLIC_PATH?>assets/select2-4.0.13/css/select2.min.css">

    <!-- APP libs -->
    <link rel="stylesheet" href="<?=PUBLIC_PATH?>css/app.css">
    <script src="<?=PUBLIC_PATH?>js/app.js"></script>

</head>
<body>

<div class="container-fluid">
    <div class="page-content-wrapper">
        <div class="page-content">
            <h4><?=APP_NAME?></h4>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            Events
                        </div>
                        <div class="card-body table-responsive">
                            <table id="event_list" class="table table-sm table-bordered table-hover table-striped table-event">
                                <thead class="">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Event</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">School</th>
                                    <th scope="col">Venue</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Distance</th>
                                    <th scope="col">Travel time</th>
                                    <th scope="col">Organisers</th>
                                    <th scope="col">Participants</th>
                                    <th scope="col">Attendees</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Add new event
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <form id="event_form" class="form form-validation" role="form" autocomplete="off" method="post" ACTION="api.php">
                                    <input class="form-control" type="hidden" id="event_id" name="event_id" value="0" />
                                    <input class="form-control" type="hidden" id="c" name="c" value="event" />
                                    <input class="form-control" type="hidden" id="m" name="m" value="saveEvent" />

                                    <div class="form-group row">
                                        <label>Event name *</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" id="event_name" name="event_name" required onfocus="clearFeedback()" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label>Event date</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" id="event_datetime" name="event_datetime" required />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label>Event description</label>
                                        <div class="input-group">
                                            <textarea class="form-control" type="text" id="description" name="description"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label>School *</label>
                                        <div class="input-group">
                                            <select class="form-control select2" id="school_id" name="school_id" required>
                                                <option value="">Select</option>
												<? foreach ($options['schools'] as $school) {?>
                                                    <option value="<?=$school['school_id']?>"><?=$school['school_name']?></option>
												<?}?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label>Venue *</label>
                                        <div class="input-group">
                                            <select class="form-control select2" id="venue_id" name="venue_id" required>
                                                <option value="">Select</option>
												<? foreach ($options['venues'] as $venue) {?>
                                                    <option value="<?=$venue['venue_id']?>"><?=$venue['venue_name']?></option>
												<?}?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-primary btn-add" type="button" onclick="showModal('venue')">+</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label>Category *</label>
                                        <div class="input-group">
                                            <select class="form-control select2" id="category_id" name="category_id" required>
                                                <option value="">Select</option>
												<? foreach ($options['categories'] as $category) {?>
                                                    <option value="<?=$category['category_id']?>"><?=$category['category_name']?></option>
												<?}?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-primary btn-add" type="button" onclick="showModal('category')">+</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label>Organisers *</label>
                                        <div class="input-group">
                                            <select class="form-control select2" id="organiser_id" name="organiser_id[]" multiple required>
												<? foreach ($options['organisers'] as $organiser) {?>
                                                    <option value="<?=$organiser['person_id']?>"><?=$organiser['person_name']?></option>
												<?}?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-primary" type="button" onclick="showModal('organiser')">+</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label>Participants</label>
                                        <div class="input-group">
                                            <select class="form-control select2" id="participant_id" name="participant_id[]" multiple >
												<? foreach ($options['non_organisers'] as $person) {?>
                                                    <option value="<?=$person['person_id']?>"><?=$person['person_name']?></option>
												<?}?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-primary" type="button" onclick="showModal('participant')">+</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label>Attendees</label>
                                        <div class="input-group">
                                            <select class="form-control select2" id="attendee_id" name="attendee_id[]" multiple >
												<? foreach ($options['non_organisers'] as $person) {?>
                                                    <option value="<?=$person['person_id']?>"><?=$person['person_name']?></option>
												<?}?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-primary" type="button" onclick="showModal('attendee')">+</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div id="alert_success" class="alert alert-success alert-hidden" role="alert"></div>
                                    <div id="alert_error" class="alert alert-danger alert-hidden" role="alert"></div>

                                    <button type="submit" id="bt_save" class="btn btn-embossed btn-primary">Save</button>
                                    <button type="button" id="bt_new" class="btn btn-embossed btn-default bt_hidden" onclick="clearForm()">New</button>
                                    <button type="button" id="bt_delete" class="btn btn-embossed btn-danger bt_hidden" onclick="confirmDeleteEvent()">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_venue" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="venue_form" class="form form-validation" role="form" autocomplete="off" method="post" ACTION="api.php">
                <div class="modal-header">
                    <h5 class="modal-title">Add new venue</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <input class="form-control" type="hidden" id="new_venue_id" name="new_venue_id" value="0" />
                        <input class="form-control" type="hidden" id="c" name="c" value="venue" />
                        <input class="form-control" type="hidden" id="m" name="m" value="saveVenue" />

                        <div class="form-group row">
                            <label>Venue name *</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="new_venue_name" name="new_venue_name" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label>Venue address *</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="new_venue_address" name="new_venue_address" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_category" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="category_form" class="form form-validation" role="form" autocomplete="off" method="post" ACTION="api.php">
                <div class="modal-header">
                    <h5 class="modal-title">Add new category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <input class="form-control" type="hidden" id="new_category_id" name="new_category_id" value="0" />
                        <input class="form-control" type="hidden" id="c" name="c" value="category" />
                        <input class="form-control" type="hidden" id="m" name="m" value="saveCategory" />

                        <div class="form-group row">
                            <label>Category name *</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="new_category_name" name="new_category_name" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_organiser" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="organiser_form" class="form form-validation" role="form" autocomplete="off" method="post" ACTION="api.php">
                <div class="modal-header">
                    <h5 class="modal-title">Add new organiser</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <input class="form-control" type="hidden" id="new_organiser_id" name="new_organiser_id" value="0" />
                        <input class="form-control" type="hidden" id="c" name="c" value="person" />
                        <input class="form-control" type="hidden" id="m" name="m" value="saveOrganiser" />

                        <div class="form-group row">
                            <label>Organiser name *</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="new_organiser_name" name="new_organiser_name" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_delete_confirm" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete event?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" data-dismiss="modal" onclick="deleteEvent()">Confirm</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


</body>
</html>