<template>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Travel One Way Details</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="flight-destinations">
                    <div class="container">
                        <div class="row">
                            <aside class="col-md-3">
                                <div class="bg-white shadow-md rounded p-3">
                                    <h3 class="text-5">Filter</h3>
                                    <hr class="mx-n3">
                                    <div class="accordion accordion-alternate style-2 mt-n3" id="toggleAlternate">
                                        <div class="card">
                                            <div class="card-header" id="stops">
                                                <h5 class="mb-0"> <a href="#" data-toggle="collapse" data-target="#togglestops" aria-expanded="true" aria-controls="togglestops">No. of Stops</a> </h5>
                                            </div>
                                            <div id="togglestops" class="collapse show" aria-labelledby="stops">
                                                <div class="card-body">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="nonstop" name="stop" class="custom-control-input">
                                                        <label class="custom-control-label" for="nonstop">Non Stop</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="1stop" name="stop" class="custom-control-input">
                                                        <label class="custom-control-label" for="1stop">1 Stop</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="2stop" name="stop" class="custom-control-input">
                                                        <label class="custom-control-label" for="2stop">2+ Stop</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="departureTime">
                                                <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#toggleDepartureTime" aria-expanded="true" aria-controls="toggleDepartureTime">Departure Time</a> </h5>
                                            </div>
                                            <div id="toggleDepartureTime" class="collapse show" aria-labelledby="departureTime">
                                                <div class="card-body">
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="earlyMorning" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="earlyMorning">Early Morning</label> <small class="text-muted float-right">12am - 8am</small> 
                                                    </div>
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="morning" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="morning">Morning</label> <small class="text-muted float-right">8am - 12pm</small> 
                                                    </div>
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="midDay" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="midDay">Mid-Day</label> <small class="text-muted float-right">12pm - 4pm</small> 
                                                    </div>
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="evening" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="evening">Evening</label> <small class="text-muted float-right">4pm - 8pm</small> 
                                                    </div>
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="night" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="night">Night</label> <small class="text-muted float-right">8pm - 12am</small> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="price">
                                                <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#togglePrice" aria-expanded="true" aria-controls="togglePrice">Price</a> </h5>
                                            </div>
                                            <div id="togglePrice" class="collapse show" aria-labelledby="price">
                                                <div class="card-body">
                                                    <p>
                                                        <input id="amount" type="text" readonly="" class="form-control border-0 bg-transparent p-0">
                                                    </p>
                                                    <div id="slider-range" class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content">
                                                        <div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 10.4167%; width: 71.25%;"></div><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 10.4167%;"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 81.6667%;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="airlines">
                                                <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#toggleAirlines" aria-expanded="true" aria-controls="toggleAirlines">Airlines</a> </h5>
                                            </div>
                                            <div id="toggleAirlines" class="collapse show" aria-labelledby="airlines">
                                                <div class="card-body">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="asianaAir" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="asianaAir">Asiana Airlines</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="americanAir" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="americanAir">American Airlines</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="airCanada" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="airCanada">Air Canada</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="airIndia" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="airIndia">Air India</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="jetAirways" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="jetAirways">Jet Airways</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="spicejet" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="spicejet">Spicejet</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="indiGo" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="indiGo">IndiGo</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="multiple" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="multiple">Multiple Airlines</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </aside>
                            <div class="col-md-9 mt-4 mt-md-0">
                                <div class="bg-white shadow-md rounded py-4">
                                    <div class="mx-3 mb-3 text-center">
                                        <h2 class="text-6 mb-4 text-dark">Delhi <small class="mx-2 text-dark">to</small>Sydney</h2>
                                    </div>
                                    <div class="text-1 bg-light-3 border border-right-0 border-left-0 py-2 px-3">
                                        <div class="row">
                                          <div class="col col-sm-2 text-center">
                                            <span class="d-none d-sm-block text-dark">Airline</span></div>
                                          <div class="col col-sm-2 text-center">Departure</div>
                                          <div class="col-sm-2 text-center d-none d-sm-block">Duration</div>
                                          <div class="col col-sm-2 text-center">Arrival</div>
                                          <div class="col col-sm-2 text-right">Price</div>
                                        </div>
                                      </div>
                                    <div class="flight-list">
                                        <div class="flight-item">
                                            <div class="row align-items-center flex-row pt-4 pb-2 px-3">
                                                <div class="col col-sm-2 text-center d-lg-flex company-info"> <span class="align-middle"><img class="img-fluid" alt="" src="http://demo.harnishdesign.net/html/quickai/images/brands/flights/indigo.png"> </span>  <span class="align-middle ml-lg-2"> <span class="d-block text-1 text-dark">IndiGo</span>  <small class="text-muted d-block">6E-2726</small> 
                                                    </span>
                                                </div>
                                                <div class="col col-sm-2 text-center time-info"> <span class="text-4">23:00</span>  <small class="text-muted d-none d-sm-block">Delhi</small> 
                                                </div>
                                                <div class="col-sm-2 text-center d-none d-sm-block time-info"> <span class="text-3">18h 55m</span>  <small class="text-muted d-none d-sm-block">Non Stop</small> 
                                                </div>
                                                <div class="col col-sm-2 text-center time-info"> <span class="text-4">18:15</span>  <small class="text-muted d-none d-sm-block">Sydney</small> 
                                                </div>
                                                <div class="col col-sm-2 text-right text-dark text-6 price">$980</div>
                                                <div class="col-12 col-sm-2 text-center ml-auto btn-book"> <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-shopping-cart d-block d-lg-none"></i> <span class="d-none d-lg-block">Book</span></a> 
                                                </div>
                                                <div class="col col-sm-auto col-lg-2 ml-auto mt-1 text-1 text-center"><a data-toggle="modal" data-target="#flight-1" href="">Flight Details</a>
                                                </div>
                                            </div>
                                            <!-- Flight Details Modal Dialog
                  ============================================= -->
                                        </div>
                                    </div>

                                    <div class="flight-list">
                                        <div class="flight-item">
                                            <div class="row align-items-center flex-row pt-4 pb-2 px-3">
                                                <div class="col col-sm-2 text-center d-lg-flex company-info"> <span class="align-middle"><img class="img-fluid" alt="" src="http://demo.harnishdesign.net/html/quickai/images/brands/flights/spicejet.png"> </span>  <span class="align-middle ml-lg-2"> <span class="d-block text-1 text-dark">Spicejet</span>  <small class="text-muted d-block">6E-2726</small> 
                                                    </span>
                                                </div>
                                                <div class="col col-sm-2 text-center time-info"> <span class="text-4">23:00</span>  <small class="text-muted d-none d-sm-block">Delhi</small> 
                                                </div>
                                                <div class="col-sm-2 text-center d-none d-sm-block time-info"> <span class="text-3">18h 55m</span>  <small class="text-muted d-none d-sm-block">Non Stop</small> 
                                                </div>
                                                <div class="col col-sm-2 text-center time-info"> <span class="text-4">18:15</span>  <small class="text-muted d-none d-sm-block">Sydney</small> 
                                                </div>
                                                <div class="col col-sm-2 text-right text-dark text-6 price">$980</div>
                                                <div class="col-12 col-sm-2 text-center ml-auto btn-book"> <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-shopping-cart d-block d-lg-none"></i> <span class="d-none d-lg-block">Book</span></a> 
                                                </div>
                                                <div class="col col-sm-auto col-lg-2 ml-auto mt-1 text-1 text-center"><a data-toggle="modal" data-target="#flight-1" href="">Flight Details</a>
                                                </div>
                                            </div>
                                            <!-- Flight Details Modal Dialog
                  ============================================= -->
                                        </div>
                                    </div>

                                    <div class="flight-list">
                                        <div class="flight-item">
                                            <div class="row align-items-center flex-row pt-4 pb-2 px-3">
                                                <div class="col col-sm-2 text-center d-lg-flex company-info"> <span class="align-middle"><img class="img-fluid" alt="" src="http://demo.harnishdesign.net/html/quickai/images/brands/flights/jetairways.png"> </span>  <span class="align-middle ml-lg-2"> <span class="d-block text-1 text-dark">Jet Airways</span>  <small class="text-muted d-block">6E-2726</small> 
                                                    </span>
                                                </div>
                                                <div class="col col-sm-2 text-center time-info"> <span class="text-4">23:00</span>  <small class="text-muted d-none d-sm-block">Delhi</small> 
                                                </div>
                                                <div class="col-sm-2 text-center d-none d-sm-block time-info"> <span class="text-3">18h 55m</span>  <small class="text-muted d-none d-sm-block">Non Stop</small> 
                                                </div>
                                                <div class="col col-sm-2 text-center time-info"> <span class="text-4">18:15</span>  <small class="text-muted d-none d-sm-block">Sydney</small> 
                                                </div>
                                                <div class="col col-sm-2 text-right text-dark text-6 price">$980</div>
                                                <div class="col-12 col-sm-2 text-center ml-auto btn-book"> <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-shopping-cart d-block d-lg-none"></i> <span class="d-none d-lg-block">Book</span></a> 
                                                </div>
                                                <div class="col col-sm-auto col-lg-2 ml-auto mt-1 text-1 text-center"><a data-toggle="modal" data-target="#flight-1" href="">Flight Details</a>
                                                </div>
                                            </div>
                                            <!-- Flight Details Modal Dialog
                  ============================================= -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>