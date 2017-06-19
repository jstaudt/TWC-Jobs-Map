
<template>
    <div class="container">
        <div class="row">
            <div>
                <h1 class="mapTitle">C2 Providers Map</h1>
            </div>
    
            <div>
                <div>
                    <label for="facility">Choose Facility Type</label>
                    <div class="select-style">
                        <select id="facility" @change="getProviders()" v-model="whereFacility" name="facility">
                            <option v-for="facility in fields.facility" v-bind:value="facility">
                              {{facility}}
                          </option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="map" class=""></div>

            <!-- modal -->
            <div id="modal"
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
                            <h3 id="myModalLabel" class="modal-title">{{modal.provider_name}}</h3>
                        </div>
                        <!-- header -->
                          
                        <!-- body -->
                        <div class="modal-body">
                            <h5>Address:</h5>
                            <p>{{modal.address}}</p>
                            <p><span>{{modal.city}}, </span><span>{{modal.state}} </span><span>{{modal.zip}}</span></p>

                            <h5>Phone Number</h5>
                            <p>{{modal.phone_number}}</p>
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
                facility : [""],
            },

            whereFacility: "",

            providers: [],

            geocodes: [],

            markers: [],

            panTo: {},

            style: mapStyle.style,

            map: {},

            modal: {
                provider_name: "",
                phone_number: "",
                address: "",
                city: "",
                zip: "",
                license: "",
            }
        }
    },

    ready() {
        this.processDataSource();
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

            for(let prop in this.modal) {
                this.modal[prop] = this.providers[id][prop];
            }
        },

        getProviders : function () {
            $(':checkbox').prop('disabled',true);
            $('select').prop('disabled',true);

            let where = {'facility': this.whereFacility};
            this.$http.post('/index', {where:where, table:"providers"}).then((response)=>{
                this.providers = response.body.providers;
                this.geocodes = response.body.geocodes;

                const infowindow = new google.maps.InfoWindow({
                    maxWidth: 200
                });
                let marker;
               
                this.clearMarkers();

                for(let prop in this.providers) {
                    if(this.geocodes.hasOwnProperty(this.providers[prop].license)) {
                        const that = this;
                        const code = this.geocodes[this.providers[prop].license];
                        
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(code.lat, code.lng),
                            map: that.map
                        }); 

                        this.markers.push(marker);

                        google.maps.event.addListener(marker, 'click', (function(marker, prop) {
                            return function() {
                                that.loadModal(prop,false);
                                $('#modal').modal('show');
                                // infowindow.setContent("<h1>Executive Chef</h1>");
                                // infowindow.open(that.map, marker);
                            }
                        })(marker, prop));
                    }
                }

            $(':checkbox').prop('disabled',false);
            $('select').prop('disabled',false);
                
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
    body{
        font-family: sans-serif;
    }

</style>
