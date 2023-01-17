<template>
  <div id="change_detail_address" class="modal">
    <div class="modal-dialog">
      <div class="modal-body">
        <span class="close-modal"></span>
        <div class="content-modal">
          <h3 class="nunito-title text-center w-100 mbottom-30">
            ubah alamat pengiriman
          </h3>
          <div class="form-change-address selected">
            <div class="w-95 m-auto d-flex align-center mbottom-20">
              <h5 class="col-3 col-sm-12 fw-400">Nama Penerima</h5>
              <div class="col-9 col-sm-12 relative">
                <input type="text" v-model="form.recipientname" />
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.recipientname.required"
                >
                  Field tidak boleh kosong
                </div>
              </div>
            </div>
            <div class="w-95 m-auto d-flex align-center mbottom-20">
              <h5 class="col-3 col-sm-12 fw-400">Nomor Telepon</h5>
              <div class="col-9 col-sm-12 relative">
                <input type="number" v-model="form.phone" />
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.phone.required"
                >
                  Field tidak boleh kosong
                </div>
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.phone.minLength"
                >
                  Nomor telepon minimal 10 angka.
                </div>
              </div>
            </div>
            <div class="w-95 m-auto d-flex align-center mbottom-20">
              <h5 class="col-3 col-sm-12 fw-400">Alamat</h5>
              <div class="col-9 col-sm-12 relative">
                <input type="text" class="address" v-model="form.address" />
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.address.required"
                >
                  Field tidak boleh kosong
                </div>
              </div>
            </div>
            <div class="w-95 m-auto d-flex align-center mbottom-20">
              <h5 class="col-3 col-sm-12 fw-400">Provinsi</h5>
              <div class="col-9 col-sm-12 relative">
                <div>
                  <Select2
                    v-model="form.province"
                    :options="provinces"
                    @select="mySelectEvent($event, 'province')"
                    @change="changeEventSelect2($event, 'province')"
                  />
                </div>
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.province.required"
                >
                  Field tidak boleh kosong
                </div>
              </div>
            </div>
            <div class="w-95 m-auto d-flex align-center mbottom-20">
              <h5 class="col-3 col-sm-12 fw-400">Kota</h5>
              <div class="col-9 col-sm-12 relative">
                <Select2
                  v-model="form.city"
                  :options="city"
                  @select="mySelectEvent($event, 'city')"
                  @change="changeEventSelect2($event, 'city')"
                />
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.city.required"
                >
                  Field tidak boleh kosong
                </div>
              </div>
            </div>
            <div class="w-95 m-auto d-flex align-center mbottom-20">
              <h5 class="col-3 col-sm-12 fw-400">Daerah</h5>
              <div class="col-9 col-sm-12 relative">
                <Select2
                  v-model="form.suburbs"
                  :options="suburbs"
                  @select="mySelectEvent($event, 'suburbs')"
                  @change="changeEventSelect2($event, 'suburbs')"
                />
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.suburbs.required"
                >
                  Field tidak boleh kosong
                </div>
              </div>
            </div>
            <div class="w-95 m-auto d-flex align-center mbottom-20">
              <h5 class="col-3 col-sm-12 fw-400">Area</h5>
              <div class="col-9 col-sm-12 relative">
                <Select2
                  v-model="form.area"
                  :options="area"
                  @select="mySelectEvent($event, 'area')"
                  @change="changeEventSelect2($event, 'area')"
                />
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.area.required"
                >
                  Field tidak boleh kosong
                </div>
              </div>
            </div>
            <div class="w-95 m-auto d-flex align-center mbottom-20">
              <h5 class="col-3 col-sm-12 fw-400">Kode Pos</h5>
              <div class="col-9 col-sm-12 relative">
                <input type="number" v-model="form.zip_code" />
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.zip_code.required"
                >
                  Field tidak boleh kosong
                </div>
              </div>
            </div>
            <div class="w-95 m-auto d-flex align-center mbottom-20">
              <h5 class="col-3 col-sm-12 fw-400">Label Alamat</h5>
              <div class="col-9 col-sm-12 relative">
                <input type="text" class="address" v-model="form.label_name" />
                <div
                  class="error"
                  v-if="submitStatus && !$v.form.label_name.required"
                >
                  Field tidak boleh kosong
                </div>
              </div>
            </div>
            <div class="w-95 m-auto">
              <!-- <button class="btn-primary-square w-100 justify-center mtop-40 mbottom-30">Ubah Pin Poin Alamat</button>
                            <div class="mbottom-50 as-main-address">
                                <span class="fs-black mright-5">Gunakan Sebagai Alamat Utama</span>
                                <img src="img/icon-checklist-grey.svg" width="15" alt="">
                            </div> -->
              <div class="d-flex align-center justify-right mbottom-20">
                <button class="btn-secondary" @click="close_modal">Batal</button>
                <button class="btn-primary mleft-5" @click="saveAddress">
                  Simpan
                </button>
              </div>
              <div class="disclaimer">
                Dengan klik 'Simpan', Anda telah menyetujui
                <router-link class="mleft-5" to="/policies?index=Aturan_dan_Kebijakan">
                    <b>Syarat &amp; Ketentuan</b>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Select2 from "v-select2-component";
import Vue from "vue";
import Vuelidate from "vuelidate";
Vue.use(Vuelidate);
import { required, minLength, between } from "vuelidate/lib/validators";
import Customer from "../../../apis/Customer";
import apiMaster from "../../../apis/Master";

export default {
  name: "ModalChangeAddressDetail.vue",
  props: ["item"],
  data() {
    return {
      address: "",
      text_address: "",
      text_province: "",
      text_city: "",
      text_area: "",
      text_suburb: "",
      provinces: [],
      city: [],
      suburbs: [],
      area: [],
      submitStatus: false,
      disabledButton: false,
      form: {
        itemid: "",
        recipientname: "",
        phone: "",
        address: "",
        label_name: "",
        city: "",
        province: "",
        zip_code: "",
        area: "",
        suburbs: "",
        latitude: "",
        longitude: "",
        isPrimary: false,
      },
    };
  },
  watch: {
    item() {
      if (Object.keys(this.item).length > 0) {
        this.form.itemid = this.item.itemid;
        this.form.recipientname = this.item.recipient_name;
        this.form.phone = this.item.phone_number;
        this.form.address = this.item.address;
        this.form.label_name = this.item.label_name;
        this.form.province = this.item.province_id.id;
        this.form.city = this.item.city_id.id;
        this.form.suburbs = this.item.regency_id.id;
        this.form.area = this.item.area_id.id;
        this.form.longitude = this.item.longitude;
        this.form.latitude = this.item.latitude;
        this.form.isPrimary = this.item.is_primary_address;
        this.province_data();
        this.city_data();
        this.suburbs_data();
        this.area_data();
        this.text_province = this.item.province_id.text;
        this.text_city = this.item.city_id.text;
        this.text_area = this.item.area_id.name;
        this.text_suburb = this.item.regency_id.text;
        this.text_address =
          this.text_province +
          " " +
          this.text_city +
          " " +
          this.text_suburb +
          " " +
          this.text_area;
        this.address = this.text_address;
        this.form.zip_code = this.item.zip_code;
      }
    },
  },
  validations: {
    form: {
      recipientname: {
        required,
        minLength: minLength(1),
      },
      phone: {
        required,
        minLength: minLength(10),
      },
      address: {
        required,
        minLength: minLength(10),
      },
      city: {
        required,
      },
      province: {
        required,
      },
      suburbs: {
        required,
      },
      area: {
        required,
      },
      zip_code: {
        required,
      },
      label_name: {
        required,
      },
    },
  },
  methods: {
    mySelectEvent({ id, text }, type) {
      if (type == "province") {
        this.text_province = text;
        this.suburbs = [];
        this.area = [];
        this.form.city = "";
        this.form.suburbs = "";
        this.form.area = "";
      }
      if (type == "city") {
        this.text_city = text;
        this.area = [];
        this.form.suburbs = "";
        this.form.area = "";
      }
      if (type == "suburbs") {
        this.text_suburb = text;
        this.form.area = "";
      }
      if (type == "area") {
        this.text_area = text;
        this.error = false;
      }
      this.text_address =
        this.text_province +
        " " +
        this.text_city +
        " " +
        this.text_suburb +
        " " +
        this.text_area;
      this.address = this.text_address;
    },
    changeEventSelect2(val, type, index) {
      if (type == "province") {
        this.city_data();
      }
      if (type == "city") {
        this.city_value = val;
        this.suburbs_data();
      }
      if (type == "suburbs") {
        this.suburbs_value = val;
        this.area_data();
      }
      if (type == "area") {
        this.area_value = val;
      }
    },

    province_data() {
      apiMaster.province().then((response) => {
        this.provinces = response.data.data;
      });
    },
    city_data() {
      apiMaster.city({ province: this.form.province }).then((response) => {
        this.city = response.data.data;
      });
    },
    suburbs_data() {
      apiMaster.subrubs({ city: this.form.city }).then((response) => {
        this.suburbs = response.data.data;
      });
    },
    area_data() {
      apiMaster.area({ suburb_id: this.form.suburbs }).then((response) => {
        this.area = response.data.data;
      });
    },
    saveAddress() {
      this.submitStatus = true;
      if (this.disabledButton == false) {
        this.disabledButton = true;
        if (this.$v.$invalid) {
          this.disabledButton = false;
        } else {
          $("#modal_load").fadeIn();
          Customer.update_address(this.form).then((response) => {
            this.disabledButton = false;
            $("#modal_load").fadeOut();
            if (response.data.code == 200) {
              this.actionAfterSave(response.data.data);
              $("#change_detail_address").fadeOut(function () {
                $("body").removeClass("overflow-hidden");
              });
            } else {
              Message.alert(response.message, "Informasi", 1000);
            }
          });
        }
      }
    },
    actionAfterSave(data) {
      $("#label-address").html(data.label_name);
      $("#address").html(data.address + ",&nbsp;");
      $("#recepient-name").html(data.recipient_name);
      $("#province").html(data.province_id.name + ",&nbsp;");
      $("#regency").html(data.regency_id.name + ",&nbsp;");
      $("#zip-code").html(data.zip_code);
      $("#phone-number").html(data.phone_number);
    },
    close_modal() {
      $("#change_detail_address").fadeOut(function () {
        $("body").removeClass("overflow-hidden");
      });
    },
  },

  components: {
    Select2,
  },
};
</script>

<style scoped>
</style>