<template>
  <div class="app-container">

	<div v-loading="loading">
		<div class="order">
			<h4>Order Id &nbsp;&nbsp;&nbsp;&nbsp;</h4>
			<p># {{order.id}}</p>
		</div>
		<div  class="order">
			<h4>Customer Name &nbsp;&nbsp;&nbsp;&nbsp;</h4>
			<p>{{order.customer_name}}</p>
		</div>
		<div  class="order">
			<h4>Customer Phone &nbsp;&nbsp;&nbsp;&nbsp; </h4>
			<p>{{order.phone}}</p>
		</div>
		<div  class="order">
			<h4>Total &nbsp;&nbsp;&nbsp;&nbsp; </h4>
			<p>{{order.total}}</p>
		</div>
		<div  class="order">
			<h4>Discount &nbsp;&nbsp;&nbsp;&nbsp;</h4>
			<p>{{order.discount}}</p>
		</div>
		
		<el-table v-loading="loading" :data="order.items" style="width: 100%">
			<el-table-column prop="id" label="Id" width="60" />
			<el-table-column prop="quantity" label="Name" width="100" />
			<el-table-column prop="price" label="Price" width="150" />
			<el-table-column prop="total" label="Total" width="150" />
			<el-table-column prop="discount" label="Discount" width="150" />
		</el-table>
	</div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      	order: {},
	list: [],
	loading: true,
    };
  },
  created() {
	const id = this.$route.params && this.$route.params.id;
    	this.getList(id);
  },
  methods: {
    async getList(id) {
      	axios.get('/api/orders/'+ id).then((response) => {
		this.order = response.data;
		this.list = response.data.items;
		this.loading = false;
      	});
    },
  },
}
</script>

<style>
	.order{
		display: block;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		margin: 10px;
	}
	.order h4{
		font-size: 14px;
		margin: 0;
		padding: 0;
		float: left;
	}
	.order p{
		font-size: 14px;
		margin: 0;
		padding: 0;
		color: grey;
	}
	
</style>