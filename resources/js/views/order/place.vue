<template>
  <div class="app-container">
	<el-form
          ref="orderForm"
          :rules="rules"
          :model="cart"
          label-position="left"
          label-width="150px"
          style="max-width: 500px"
        >
          <el-form-item label="Name" prop="name" required="">
            <el-input v-model="cart.name" />
          </el-form-item>
          <el-form-item label="Phone" prop="phone" required="">
            <el-input v-model="cart.phone" />
          </el-form-item>
          <el-form-item label="Address" prop="address" required="">
            <el-input v-model="cart.address" />
          </el-form-item>
        </el-form>

	<el-table v-loading="loading" :data="list" style="width: 100%">
	<el-table-column prop="product.id" label="Id" width="60" />
	<el-table-column prop="product.name" label="Name" width="150" />
	<el-table-column prop="quantity" label="Quantity" width="150" />
	<el-table-column prop="product.price" label="Price" width="150" />
	<el-table-column label="Product Total" width="150" >
		<template slot-scope="scope">
			<p>{{scope.row.product.price * scope.row.quantity}}</p>
		</template>
	</el-table-column>
	<el-table-column prop="paid" label="Paid" width="150" />
	<el-table-column prop="discount" label="Discount" width="150" />
	<el-table-column label="Due" width="150" >
		<template slot-scope="scope">
			{{(scope.row.product.price * scope.row.quantity) - (parseInt(scope.row.discount) + parseInt(scope.row.paid))}}
		</template>
	</el-table-column>
	</el-table>

	<p>Total {{this.cart.total}}</p>
	<p>Paid {{this.cart.paid}}</p>
	<p>Discount {{this.cart.discount}}</p>
	<p>Due {{this.cart.due}}</p>

	<div slot="footer" class="dialog-footer">
          <el-button @click="close">{{ $t('table.cancel') }}</el-button>
          <el-button type="primary" @click="placeOrder">
            Place Order
          </el-button>
        </div>

  </div>
</template>

<script>
import axios from 'axios';
export default {
	  data() {
		return {
		type: '',
		loading: true,
		list: null,
		cart: {},
		rules: {
			code: [
			{
			required: true,
			message: 'Code is required',
			trigger: blur,
			},
		],
      },
	}},
	created(){
		this.getList();	
	},
	methods: {
		getList(){
			this.list = this.$store.getters.carts;
			let t = 0;
			let d = 0;
			let due = 0;
			let paid = 0;
			this.list.forEach(element => {
				t = t + (element.product.price * element.quantity);
				d += parseInt(element.discount);
				paid += parseInt(element.paid);
				due += ((parseInt(element.product.price) * parseInt(element.quantity)) - (parseInt(element.discount) + parseInt(element.paid)));
			});
			this.cart.total = t;
			this.cart.discount = d;
			this.cart.due = due;
			this.cart.paid = paid;
			this.loading = false;
		},
		placeOrder(){
			this.cart.list = this.list;
			this.$refs['orderForm'].validate((valid) => {
        			if (valid) {
					axios.post('/api/orders/',  this.cart).then((response) => {
					this.$message({
						message: 'Order placed successfully',
						type: 'success',
						duration: 5 * 1000,
					});
					this.productCreating = false;
					this.$router.back();
					this.close();
				});
			}});
		},
		close(){
			this.cart = {};
			this.list = {};
		}
	},
}
</script>

<style>

</style>