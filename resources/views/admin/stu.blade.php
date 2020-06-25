@extends('admin.admin')

@section('content')
<div class="inner-block">
	<div class="search-box">
		<!-- <form> -->
		<input type="text" v-model="search_item" placeholder="جستوجو..." required="">
		<input type="submit" @click="search_stu" value="">
		<!-- </form> -->
	</div>
	<div class="clearfix"> </div>
	<div class="chit-chat-layer1">
		<table v-if="all_stu.length" class="table table-striped table-bordered table-hover table-condensed col-md-12 saeid_block">
			<thead>
				<th>ردیف</th>
				<th>نام</th>
				<th>موبایل</th>
				<th>کد ملی</th>
				<th>مشاور</th>
				<th>پایه</th>
				<th>رشته</th>
				<th>عکس</th>
				<th>موجودی</th>
				<th>وضعیت</th>
				<!-- <th>حذف</th> -->
			</thead>
			<tbody>
				<tr v-for="stu in all_stu">
					<td>@{{stu.id}}</td>
					<td>@{{stu.name}}</td>
					<td>@{{stu.mobile}}</td>
					<td>@{{stu.nation_code}}</td>
					<td>@{{stu.mosh_id}}</td>
					<td v-if="stu.base_id == 0">نهم</td>
					<td v-if="stu.base_id == 1">دهم</td>
					<td v-if="stu.base_id == 2">یازدهم</td>
					<td v-if="stu.base_id == 3">دوازدهم</td>
					<td v-if="!stu.base_id"> </td>
					<td v-if="stu.r_id == 0">ریاضی فیزیک</td>
					<td v-if="stu.r_id == 1">تجربی</td>
					<td v-if="stu.r_id == 2">انسانی</td>
					<td><a :href="'images/'+stu.img"><img width="70" height="70" :src="'images/'+stu.img" alt=""></a></td>
					<td>@{{stu.rest}}</td>
					<td v-if="stu.status == 0">ثبت شده</td>
					<td v-if="stu.status == 1">اهراز هویت</td>
					<td v-if="stu.status == 2">اطلاعات اولیه</td>
					<td v-if="stu.status == 3">فعال</td>
					<!-- <td class="td_delete" @click="slider_img(stu.id)"><i class="fa fa-trash"></i></td> -->
				</tr>
			</tbody>

		</table>
	</div>
</div>
@endsection