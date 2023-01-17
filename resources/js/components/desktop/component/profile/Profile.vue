<template>
    <div :class="is_profilemenu ? 'flex-basis-50'
        :'flex-basis-50 show-profile'">
        <div class="box-90p-white">
            <div class="row-100 profile-pic-user">
                <img class="d-block photo-profile" v-if="profile.image" :src="imgDataUrl">
                <img class="d-block dummy-profile" v-else src="/img/dummy_profile.svg" alt="">
                <div class="button-upload" @click="crop_modal">
                    <label for="img_profile">
                        <div class="upload-icon">
                            <img class="icon" src="/img/ic_camera.svg">
                        </div>
                    </label>
                    <!-- <input id="img_profile" type="file" class="add-image" @click="crop_modal" > -->
                </div>
            </div>
            <div class="id">
                <div class="row-100 name">
                    <div class="buyer-name">
                        <img src="/img/icon_profile.svg" alt="">
                        {{profile.name}}
                    </div>
                </div>
                <div class="row-100 transaction">
                    <div class="buyer-transaction">
                        <img src="/img/icon_transaction.svg" alt="">
                        <div class="w-100 d-flex align-center justify-between">
                            <h5>Total Transaksi</h5>
                            <h5>{{total_payment}} x</h5>
                        </div>
                    </div>
                </div>
                <div class="row-100 point">
                    <div class="buyer-point">
                        <img src="/img/icon_hotpoint_profile.svg" alt="">
                        <div class="w-100 d-flex align-center justify-between">
                            <h5>hot point</h5>
                            <h5>{{ profile.point | NumberFormat }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal_crop_image" class="modal">
            <div class="modal-body">
                <my-upload field="img"
                    @crop-success="cropSuccess"
                    @crop-upload-success="cropUploadSuccess"
                    @crop-upload-fail="cropUploadFail"
                    v-model="show"
                    :lang-ext="this.en"
                    :width="300"
                    :height="300"
                    :params="params"
                    :headers="headers"
                    img-format="png">
                </my-upload>
            </div> 
        </div>
    </div>
</template>

<script>
import ProfileMenu from "./ProfileMenu";
import HighLightTwoColumn from "../product/HighLightTwoColumn";
import HighLightImageOnly from "../product/HighLightImageOnly";
import apiCustomer from "../../../../apis/Customer";
import Message from '../../../../utils/Message'
import myUpload from 'vue-image-crop-upload';
export default {
    name: "Profile.vue",
    data(){
        return{
            profile : { },
            total_payment : '',
            show: true,
            params: {
                token: '123456798',
                name: 'avatar'
            },
            headers: {
                smail: '*_~'
            },
            imgDataUrl: '',
            en: {
                hint: 'klik atau tarik file di sini untuk mengunggah',
                loading: 'Sedang mengunggah ...',
                noSupported: 'Browser tidak mendukung, silahkan gunakan versi IE10+ atau browser lainnya',
                success: 'Upload Sukses',
                fail: 'Upload Gagal',
                preview: 'Preview',
                btn: {
                off: 'Batal',
                close: 'Tutup',
                back: 'Kembali',
                save: 'Simpan'
                },
                error: {
                onlyImg: 'Image only',
                outOfSize: 'Image exceeds size limit: ',
                lowestPx: 'Image\'s size is too low. Expected at least: '
                }
            },
            is_profilemenu: false,
        }
    },
    components : {
        Message,
        'my-upload' : myUpload
    },
    watch: {
        $route(to, from) {
            this.is_profilemenu = this.isShowProfile(this.$route.name)
        }
    },
    mounted(){
        apiCustomer.profile().then( response => {
            this.profile = response.data
            this.imgDataUrl = response.data.image
            //alert(this.profile.point)
            this.$emit('myPoint' , this.profile.point)
        });
        this.get_total_payment();
        
        $('.vicp-close').on("click", this.close_modal)
        $('.vicp-operate').on("click", this.close_modal)
    },
    methods :{
        saveProfilePictures(event){
            const fd = new FormData();
            fd.append('profile_image' , event.target.files[0])
            apiCustomer.saveProfilePictures(fd).then( response => {
                if(response.data.code == 200){
                    this.profile.image = response.data.data.image
                    this.$emit('updatePicture', {image : response.data.data.image})
                }else{
                    Message.alert(response.data.message , 'Informasi' , 1500)
                }
            });
        },
        get_total_payment(){
            apiCustomer.user_total_payment().then( response => {
                // this.total_payment = (response.data.data).replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                this.total_payment = response.data.data
            });
        },
        crop_modal(){
            $("#modal_crop_image").fadeIn(function () {
                $("body").addClass('overflow-hidden');
            });
        },
        close_modal(){
            $('#modal_crop_image').fadeOut(function () {
                $("body").removeClass('overflow-hidden');
            });
        },
        toggleShow() {
                this.show = !this.show;
            },
            cropSuccess(imgDataUrl, field){
                console.log('-------- crop success --------');
                this.imgDataUrl = imgDataUrl;
                console.log(this.imgDataUrl);
                const fd = new FormData();
                fd.append('profile_image' , this.imgDataUrl)
                apiCustomer.saveProfilePictures(fd).then( response => {
                    if(response.data.code == 200){
                        this.profile.image = response.data.data.image
                        $('#modal_crop_image').fadeOut(function () {
                            $("body").removeClass('overflow-hidden');
                        });
                    }else{
                        Message.alert(response.data.message , 'Informasi' , 1500)
                    }
                });
            },
            cropUploadSuccess(jsonData, field){
                console.log('-------- upload success --------');
                console.log(jsonData);
                console.log('field: ' + field);
            },
            cropUploadFail(status, field){
                console.log('-------- upload fail --------');
                console.log(status);
                console.log('field: ' + field);
            }
    },
    isShowProfile(routername){
        let arr_routername = ["personalinfo"]
            if (arr_routername.includes(routername)){
                return true;
            }
            return false;
    }
}
</script>
