
<template>
    <div class="container">
        <div class="row">
            <div>
                <h1>Welcome to Texas Jobs Portalsssss</h1>
            </div>
            <div>
                <div>
                    <label for="entry_level">Entry Level</label>
                    <div>
                        <input id="entry-level" type="checkbox" name="entry_level">
                    </div>
                    <label for="occupational_category">Choose a Category</label>
                    <div class="select-style">
                        <select id="category" v-model="where.occupational_category" name="occupational_category">
                            <option v-for="category in fields.category" v-bind:value="category">
                              {{category}}
                          </option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="map" class=""></div>

            <!-- modal -->
            <div id="fsModal"
                 class="modal animated fade"
                 tabindex="-1"
                 role="dialog"
                 aria-labelledby="myModalLabel"
                 aria-hidden="true">

              <!-- dialog -->
                <div class="modal-dialog">

                    <!-- content -->
                    <div class="modal-content">

                        <!-- header -->
                        <div class="modal-header">
                            <h1 id="myModalLabel" class="modal-title">{{modal.job_title}}</h1>
                        </div>
                        <!-- header -->
                          
                        <!-- body -->
                        <div class="modal-body">
                            <h3>Address:</h3>

                            <p>{{modal.address}}</p>
                            <p><span>{{modal.city}}, </span><span>{{modal.state}} </span><span>{{modal.zip}}</span></p>

                            <h3>Posting ID:</h3>

                            <p>{{modal.id}}</p>

                            <h3>Entry Level:</h3>

                            <p>{{modal.entry_level}}</p>

                            <h3>Occupatinal Category:</h3>

                            <p>{{modal.occupational_category}}</p>

                            <h3>Openings:</h3>

                            <p>{{modal.openings}}</p>

                            <h3>Work Week Code:</h3>

                            <p>{{modal.work_week_code}}</p>

                            <h3>Assigned Staff Name:</h3>

                            <p>{{modal.assigned_staff}}</p>

                            <h3>Email:</h3>

                            <p><a href="mailto:{{modal.email}}">{{modal.email}}</a></p>

                            <h3>Actions:</h3>
                            <a target="_blank" role="button" class="btn btn-primary apply" href="{{modal.link}}">Apply Now</a>
                            <a role="button" class="btn btn-primary apply" href="mailto:?to=&subject={{modal.job_title}}&body=Address:%0D%0A{{modal.address}}%0D%0A{{modal.city}} {{modal.state}} {{modal.zip}}%0D%0A%0D%0APosting ID:%0D%0A{{modal.id}}%0D%0A%0D%0AEntry Level:%0D%0A{{modal.entry_level}}%0D%0A%0D%0AOccupational Category:%0D%0A{{modal.occupational_category}}%0D%0A%0D%0AOpenings:%0D%0A{{modal.openings}}%0D%0A%0D%0AWork Week Code:%0D%0A{{modal.work_week_code}}%0D%0A%0D%0AAssigned Staff Name:%0D%0A{{modal.assigned_staff}}%0D%0A%0D%0AEmail:%0D%0A{{modal.email}}%0D%0A%0D%0ALink:%0D%0A{{encodeURIComponent(modal.link)}}">Email This Job</a>

                        </div>
                          <!-- body -->

                        <!-- footer -->
                        <div class="modal-footer">
                            <button class="btn btn-secondary"
                                    data-dismiss="modal">
                              close
                            </button>
                        </div>
                        <!-- footer -->

                    </div>
                        <!-- content -->
                </div>
                    <!-- dialog -->
            </div>
            <!-- modal -->
        </div>
    </div>
</template>

<script>
import mapStyle from './mapStyle.vue';

export default {
    props: ['category'],

    events: {
        googleMapsLoaded: function() {
            this.initMap();
        }
    },

    data: function() {
        return {
            fields: {
                category: [""],
            },

            where: {
                entry_level: "No",
                occupational_category: "",
            },

            jobs: [],

            geocodes: [],

            markers: [],

            panTo: {},

            style: mapStyle.style,

            map: {},

            modal: {
                job_title: "",
                job_description: "",
                link: "",
                employer: "",
                email: "",
                address: "",
                id: "",
                openings: "",
                work_week_code: "",
                max_pay_rate: "",
                min_pay_rate: "",
                assigned_staff: "",
                city: "",
                state: "",
                zip: "",
                entry_level: "",
                occupational_category: "",
            },
        }
    },

    ready() {
        this.processDataSource();
    },

    watch: {
        where: {
            handler: function(val,oldVal) {
                let counter = 0;

                for(let prop in val) {
                    if (!val[prop].length) continue;
                    if (typeof val[prop] != 'undefined') counter ++;
                }

                if(counter >= 2) this.getJobs(val);
            },
            deep: true
        },
    },

    methods: {
        processDataSource : function() {
            for(let prop in this.fields) {

                //loop through this.props, and pass values to corresponding fields
                for(let value of this[prop]) {
                    this.fields[prop].push(value[prop]);
                }
            }
        },

        loadModal: function (id) {
            for(let prop in this.modal) {
                this.modal[prop] = this.jobs[id][prop];
            }
        },

        getJobs : function (where) {
            $(':checkbox').checkboxpicker().prop('disabled',true);
            $('#category').prop('disabled',true);

            this.$http.post('/index', {where:where}).then((response)=>{
                this.jobs = response.body.jobs;
                this.geocodes = response.body.geocodes;

                const infowindow = new google.maps.InfoWindow({
                    maxWidth: 200
                });
                let marker;
               
                this.clearMarkers();

                for(let prop in this.jobs) {
                    if(this.geocodes.hasOwnProperty(this.jobs[prop].id)) {
                        const that = this;
                        const code = this.geocodes[this.jobs[prop].id];
                        
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(code.lat, code.lng),
                            map: that.map
                        }); 

                        this.markers.push(marker);

                        google.maps.event.addListener(marker, 'click', (function(marker, prop) {
                            return function() {
                                that.loadModal(prop);
                                $('#fsModal').modal('show');
                                // infowindow.setContent("<h1>Executive Chef</h1>");
                                // infowindow.open(that.map, marker);
                            }
                        })(marker, prop));
                    }
                }

                $(':checkbox').checkboxpicker().prop('disabled',false);
                $('#category').prop('disabled',false);
                
            }, (response)=>{
                //unsuccessful
            });
        },

        clearMarkers: function () {
            const map = this.map;
            const markers = this.markers;
            for(let i=0; i < this.markers.length; i++) {
                markers[i].setMap(null);
            }
            this.markers = [];
        },

        initMap: function () {
            const that = this;
            
            //here until timing ironed out, initialize fancy checkbox
            $('#entry-level').checkboxpicker();
            $('#entry-level').on('change', function() {
                that.where.entry_level = this.checked ? "Yes" : "No";
            });

            $('#entry-level').prop('checked',false);

            this.map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                styles: that.style
            });

            function error(err) {
              alert(`ERROR(${err.code}): ${err.message}`);
            };

            if (navigator.geolocation) {
                const that = this;
                navigator.geolocation.getCurrentPosition(function (position) {
                    const initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                    that.map.setCenter(initialLocation);

                    for(let prop in this.jobs) {
                        if(this.geocodes.hasOwnProperty(this.jobs[prop].id))console.log(this.job);
                    }
                },error);
            }
        }
    },
}
</script>
<style>
input {
    font-size: 16px;
}

#map {
    height: 400px;
    width: 100%;
    margin-top: 10px;
    margin-bottom: 30px;
}

@media only screen and (min-device-width: 320px) and (max-device-width: 736px) {
    select:focus,
    input:focus {
        font-size: 16px;
        background: #eee;
    }

    .container {
        margin-left: 10px;
        margin-right: 10px;
    }

    .select-style {
        padding: 0;
        margin: 0;
        border: 1px solid #ccc;
        width: 90%;
        border-radius: 3px;
        overflow: hidden;
        background-color: #fff;
    }

    .select-style select {
        padding: 5px 8px;
        width: 130%;
        border: none;
        box-shadow: none;
        background-color: transparent;
        background-image: none;
        -webkit-appearance: none;
           -moz-appearance: none;
                appearance: none;
    }

    .select-style select:focus {
        outline: none;
    }
}

@media screen and (min-device-width: 1200px) { 
    .select-style {
        padding: 0;
        margin: 0;
        border: 1px solid #ccc;
        width: 50%;
        border-radius: 3px;
        overflow: hidden;
        background-color: #fff;
    }

    .select-style select {
        padding: 5px 8px;
        width: 130%;
        border: none;
        box-shadow: none;
        background-color: transparent;
        background-image: none;
        -webkit-appearance: none;
           -moz-appearance: none;
                appearance: none;
    }

    .select-style select:focus {
        outline: none;
    }
}    
</style>