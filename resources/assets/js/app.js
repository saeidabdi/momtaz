
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Swal from 'sweetalert2'
import axios from 'axios';

window.Vue = require('vue');
// loding component
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('dropzone-component', require('./components/DropzoneComponent.vue'));
Vue.component('fileexam-component', require('./components/FileExamComponent.vue'));

const app = new Vue({
    el: '#app',
    data: {
        isLoading: false, username: '', pass: '', logined: '', name: '', img_addr: '', message: '',
        all_stu: [], search_item: '',
        paye_id: '', reshte_id: '', lesson_name: '', lesson_id: '', all_lesson: [],
        all_mosh: [],
        plan_title: '', plan_mother: 0, plan_status: '', plan_price: '', plan_isend: 0, plan_isexam: 0,all_plan : [],plan_id:'',file_addr:'',
    },
    components: {
        Loading,
    },
    mounted() {
        this.getuser();
    },
    methods: {
        login() {
            this.isLoading = true;
            axios
                .post('/login', {
                    username: this.username,
                    pass: this.pass

                }).then(response => {
                    this.isLoading = false;
                    if (response.data.username != undefined) {
                        Swal.fire('', 'مدیر گرامی ' + response.data.name + ' شما وارد شدید', 'success');
                        location.href = "/dashbord";

                    } else {
                        Swal.fire('', 'کاربر وجود ندارد', 'warning');

                    }

                }, response => {
                    this.isLoading = false;
                    Swal.fire('', 'مشکل در اتصال به سرور', 'warning');
                });
        },
        getuser() {
            axios.get('/getuser').then(response => {
                if (response.data.username != undefined) {
                    if (window.location.pathname == '/slider') {
                        this.get_imag_slide();
                        this.get_message();
                    }
                    if (window.location.pathname == '/stu') {
                        this.get_last_stu();
                    }
                    if (window.location.pathname == '/mosh') {
                        this.get_last_mosh();
                    }
                    if (window.location.pathname == '/plan') {
                        this.get_plan();
                    }
                    this.logined = 1;
                    this.username = response.data.username
                    this.name = response.data.name
                } else {
                    // location.href = '/';
                    this.logined = '';
                }
            });
        },
        exit_admin() {
            this.isLoading = true;
            axios
                .get('/exit_admin')
                .then(response => {
                    this.isLoading = false
                    location.href = "/";

                }, response => {
                    Swal.fire('', ' شما خارج شدید', 'success');
                });
        },
        // option
        funcaddrimg(val) {
            this.img_addr = val;
            this.get_imag_slide();
        },
        get_imag_slide() {
            this.isLoading = true
            axios
                .get('/get_imag_slide')
                .then(response => {
                    this.isLoading = false;
                    this.all_img_slider = response.data;
                })
        },
        slider_img(id) {
            this.isLoading = true
            axios
                .post('/slider_img', { id: id })
                .then(response => {
                    this.isLoading = false
                    Swal.fire('', response.data.mes, 'success');
                    this.get_imag_slide();
                })
        },
        get_message() {
            axios
                .get('/get_message')
                .then(response => {
                    this.message = response.data.vlaue;
                })
        },
        edit_message() {
            this.isLoading = true
            axios
                .post('/edit_message', { message: this.message })
                .then(response => {
                    this.isLoading = false
                    Swal.fire('', 'پیام بروزرسانی شد', 'success');
                })
        },
        // stu
        get_last_stu() {
            axios.get('/get_stu')
                .then(response => {
                    this.all_stu = response.data;
                })

        },
        search_stu() {
            if (this.search_item) {
                this.isLoading = true
                axios
                    .post('/search_stu', {
                        name: this.search_item
                    })
                    .then(response => {
                        this.isLoading = false
                        this.all_stu = response.data;
                    })
            } else {

            }
        },
        // lesson
        add_lesson() {
            this.isLoading = true;
            axios
                .post('/add_lesson', {
                    name: this.lesson_name,
                    paye_id: this.paye_id,
                    reshte_id: this.reshte_id,
                    id: this.lesson_id
                }).then(response => {
                    this.isLoading = false
                    this.get_lesson();
                    Swal.fire('', response.data.mes, 'success')
                })
        },
        get_lesson(a = 0) {
            if (a == 1) {
                this.isLoading = true
                axios.post('/get_lesson', {
                    p_id: this.paye_id,
                    r_id: this.reshte_id,
                }).then(response => {
                    this.isLoading = false
                    this.all_lesson = response.data;
                })
            } else {
                axios.post('/get_lesson', {
                    p_id: this.paye_id,
                    r_id: this.reshte_id,
                }).then(response => {
                    this.all_lesson = response.data;
                })
            }
        },
        //mosh
        get_last_mosh() {
            axios.get('/get_mosh')
                .then(response => {
                    this.all_mosh = response.data;
                })

        },
        search_mosh() {
            if (this.search_item) {
                this.isLoading = true
                axios
                    .post('/search_mosh', {
                        name: this.search_item
                    })
                    .then(response => {
                        this.isLoading = false
                        this.all_mosh = response.data;
                    })
            } else {

            }
        },
        unactive_mosh(id) {
            this.isLoading = true;
            axios
                .post('/unactive_mosh', { id: id })
                .then(response => {
                    this.isLoading = false;
                    Swal.fire('', 'با موفقیت حذف شد', 'success')
                })
        },
        // planing
        funcgetaddr(val) {
            console.log(val)
            this.img_addr = val;
        },
        add_plan() {
            if (this.plan_title){
                this.isLoading = true
                if (this.plan_isend) {
                    var plan_isend = 1;
                    if(this.plan_isexam){
                        var plan_isexam = 1
                    }else{
                        var plan_isexam = 0;
                    }  
                } else {
                    var plan_isend = 0;
                }
                axios
                    .post('/add_plan', {
                        title: this.plan_title,
                        parent: this.plan_mother,
                        is_ready: this.plan_status,
                        price: this.plan_price,
                        img: this.img_addr,
                        is_end: plan_isend,
                        plan_isexam : plan_isexam,
                        file_addr : this.file_addr,
                        id : this.plan_id,
                    }).then(response => {
                        this.isLoading = false;
                        this.get_plan();
                        Swal.fire('', response.data.mes, 'success')
                    })
            }
        },
        get_plan(){
            this.isLoading = true
            axios.get('/get_plan')
            .then(response => {
                this.all_plan = response.data;
                this.isLoading = false
            })
        },
        delete_plan(id) {
            this.isLoading = true;
            axios
                .post('/delete_plan', { id: id })
                .then(response => {
                    this.isLoading = false;
                    this.get_plan();
                    Swal.fire('', 'با موفقیت حذف شد', 'success')
                })
        },
        plan_edit(plan){
            this.plan_id = plan.id;
            this.plan_title = plan.title;
            this.plan_status = plan.is_ready;
            this.plan_price = plan.price;
            this.img_addr = plan.img;
            this.plan_isend = plan.is_end;
            this.plan_isexam = plan.is_exam;
        },
        funcgetfile(val) {
            this.file_addr = val;
        },
    }
});
