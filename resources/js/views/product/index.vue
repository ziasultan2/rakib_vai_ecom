<template>
  <div class="app-container">
	<h3>Product</h3>
	<div class="filter-container">
		<el-input
        v-model="query.keyword"
        :placeholder="$t('table.keyword')"
        style="width: 200px"
        class="filter-item"
        clearable
        @keyup.enter.native="handleFilter"
      />
      <el-button
        v-waves
        class="filter-item"
        type="primary"
        icon="el-icon-search"
        @click="handleFilter"
      >{{ $t('table.search') }}</el-button>
      <el-button
        class="filter-item"
        style="margin-left: 10px"
        type="primary"
        icon="el-icon-plus"
        @click="handleCreate"
      >{{ $t('table.add') }}</el-button>
	
	</div>

	<div class="table">
	  <el-table v-loading="loading" :data="list" style="width: 100%">
		<el-table-column prop="id" label="Id" width="60" />
		<el-table-column prop="name" label="Name" width="150" />
		<el-table-column prop="price" label="Price" width="150" />
		<el-table-column prop="description" label="Description" width="150" />
		<el-table-column prop="quantity" label="Quantity" width="150" />
		<el-table-column label="Operations" width="200">
			<template slot-scope="scope">
				<el-button
				type="success"
				icon="el-icon-edit"
				@click="edit(scope.row)"
				circle
				></el-button>
				<el-button
				type="success"
				icon="el-icon-delete"
				@click="show(scope.row)"
				circle
				></el-button>
			</template>
		</el-table-column>
	  </el-table>
	  <el-dialog :title="'ADD NEW Product'" :visible.sync="dialogFormVisible">
      <div v-loading="productCreating" class="form-container">
        <el-form
          ref="productForm"
          :rules="rules"
          :model="product"
          label-position="left"
          label-width="150px"
          style="max-width: 500px"
        >
		<el-form-item label="Name">
		<el-input v-model="product.name" />
		</el-form-item>
		<el-form-item label="Description">
		<el-input v-model="product.description" />
		</el-form-item>
		<el-form-item label="Price">
		<el-input v-model="product.price" />
		</el-form-item>
		<el-form-item label="Stock">
		<el-input v-model="product.quantity" />
		</el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="close">{{ $t('table.cancel') }}</el-button>
          <el-button v-if="type == 'create'" type="primary" @click="createProduct">
            CREATE
          </el-button>
          <el-button v-if="type == 'edit'" type="primary" @click="updateProduct">
            UPDATE
          </el-button>
        </div>
      </div>
    </el-dialog>
    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      @pagination="getList"
    />
	</div>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import waves from '@/directive/waves';
import permission from '@/directive/permission';
const ProductResource = new Resource('products');
import axios from 'axios';
export default {
  name: 'ProductList',
  components: { Pagination },
  directives: { waves, permission },
  data(){
    return {
      type: 'create',
      list: null,
      loading: false,
      product: {},
      dialogFormVisible: false,
      productCreating: false,
      total: 0,
      query: {
	page: 1,
	limit: 10,
	keyword: '',
      },
      rules: {
	name: [
	  { required: true, message: 'Please enter name', trigger: 'blur' },
	],
	detail: [
	  { required: true, message: 'Please enter detail', trigger: 'blur' },
	],
	price: [
	  { required: true, message: 'Please enter price', trigger: 'blur' },
	],
	quantity: [
	  { required: true, message: 'Please enter quantity', trigger: 'blur' },
	],
      }, 
    }
  },
  created(){
    this.getList();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await ProductResource.list(this.query);
      this.list = data;
      console.log(data);
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    handleFilter() {
      this.getList();
    },
    handleCreate(){
      this.driver = {};
      this.type = 'create';
      this.dialogFormVisible = true;
      this.product = {};
      this.$nextTick(() => {
        this.$refs['productForm'].clearValidate();
       });
    },
    createProduct(){
	    ProductResource.store(this.product).then((r) => console.log(r));
    },
    async updateProduct(){
      await axios.put('/api/products/'+this.product.id, this.product).then((response) => {
        this.$message({
            message: 'Product updated successfully',
            type: 'success',
            duration: 5 * 1000,
        });
        this.productCreating = false;
        this.getList();
        this.close();
    });
    },
    edit(row){
      this.type = 'edit';
      this.product = row;
      this.dialogFormVisible = true;
    },
    close(){
      this.dialogFormVisible = false;
      this.product = {};
      this.getList();
    },
  }
};
</script>

<style>

</style>
