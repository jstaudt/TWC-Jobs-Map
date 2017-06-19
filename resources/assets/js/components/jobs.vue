
<template>
    <div class="container">
        <div class="row">
            <div>
                <h1 class="mapTitle">C2 Jobs Map</h1>
            </div>
            <div v-show="!showProviders">
                <label v-show="this.where.entry_level=='Yes'" for="entry_level">Entry Level On</label>
                <label v-show="this.where.entry_level=='No'" for="entry_level">Entry Level Off</label>
                <div>
                    <label class="switch job-toggle">
                        <input id="entry-level" type="checkbox" name="entry_level">
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="categorySelect">
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
            <div id="jobModal"
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
                            <h3 id="myModalLabel" class="modal-title">{{modal.job_title}}</h3>
                        </div>
                        <!-- header -->
                          
                        <!-- body -->
                        <div class="modal-body">
                            <h5>Address:</h5>

                            <p>{{modal.address}}</p>
                            <p><span>{{modal.city}}, </span><span>{{modal.state}} </span><span>{{modal.zip}}</span></p>

                            <h5>Posting ID:</h5>

                            <p>{{modal.id}}</p>

                            <h5>Entry Level:</h5>

                            <p>{{modal.entry_level}}</p>

                            <h5>Occupatinal Category:</h5>

                            <p>{{modal.occupational_category}}</p>

                            <h5>Openings:</h5>

                            <p>{{modal.openings}}</p>

                            <h5>Work Week Code:</h5>

                            <p>{{modal.work_week_code}}</p>

                            <h5>Assigned Staff Name:</h5>

                            <p>{{modal.assigned_staff}}</p>

                            <h5>Email:</h5>

                            <p><a href="mailto:{{modal.email}}">{{modal.email}}</a></p>

                            <h5>Actions:</h5>
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
    props: ['category','facility'],

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
                if(typeof this[prop] == 'undefined') continue;
                //loop through this.props, and pass values to corresponding fields
                for(let value of this[prop]) {
                    this.fields[prop].push(value[prop]);
                }
            }
        },

        loadModal: function (id,isJob) {
            let modal = isJob ? this.modal : this.providerModal;
            let obj = isJob ? this.jobs : this.providers;

            for(let prop in modal) {
                modal[prop] = obj[id][prop];
            }
        },

        getJobs : function (where) {
            $(':checkbox').prop('disabled',true);
            $('#category').prop('disabled',true);

            this.$http.post('/index', {where:where, table:"jobs"}).then((response)=>{
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
                                that.loadModal(prop,true);
                                $('#jobModal').modal('show');
                                // infowindow.setContent("<h1>Executive Chef</h1>");
                                // infowindow.open(that.map, marker);
                            }
                        })(marker, prop));
                    }
                }

            $(':checkbox').prop('disabled',false);
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
                },error);
            }
        }
    },
}
</script>
<style scoped>
    
</style>