@extends('admin.admin')

@section('content')
<div class="inner-block">
	<div class="search-box">
		<!-- <form> -->
		<input type="text" v-model="search_item" placeholder="جستوجو..." required="">
		<input type="submit" @click="search_mosh" value="">
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
				<th>کد مشاور</th>
				<th>عکس</th>
				<th>آیکن</th>
				<th>پیام</th>
				<th>دانش آموزان</th>
				<th>غیر فعال</th>
			</thead>
			<tbody>
				<tr v-for="stu in all_mosh">
					<td>@{{stu.id}}</td>
					<td>@{{stu.name}}</td>
					<td>@{{stu.mobile}}</td>
					<td>@{{stu.nation_code}}</td>
					<td>@{{stu.code}}</td>
					<td><a :href="'images/'+stu.img"><img width="70" height="70" :src="'images/'+stu.img" alt=""></a></td>
					<td><a :href="'images/'+stu.logo"><img width="70" height="70" :src="'images/'+stu.logo" alt=""></a></td>
					<td>@{{stu.message}}</td>
					<td class="td_delete" @click=""><i class="fa fa-users"></i></td>
					<td class="td_delete" @click="unactive_mosh(stu.id)"><i class="fa fa-trash"></i></td>
				</tr>
			</tbody>

		</table>
	</div>
</div>
@endsection