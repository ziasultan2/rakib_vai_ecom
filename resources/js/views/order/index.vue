<template>
  <div class="app-container">
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

      <el-button
        class="filter-item"
        style="margin-left: 10px"
        type="primary"
        icon="el-icon-plus"
        @click="goToCart"
      >{{ cartTotal }}</el-button>
    </div>
    <el-table v-loading="loading" :data="list" style="width: 100%">
      <el-table-column prop="id" label="Id" width="60" />
      <el-table-column prop="customer_name" label="Name" width="150" />
      <el-table-column prop="phone" label="Phone" width="150" />
      <el-table-column prop="discount" label="Discount" width="100" />
      <el-table-column prop="total" label="Total" width="100" />
      <el-table-column prop="address" label="Address" width="100" />
      <el-table-column prop="due" label="Due" width="100" />
      <el-table-column prop="status" label="Status" width="100" />
      <el-table-column label="Operations" width="200">
        <template slot-scope="scope">
          <router-link
            :to="
              '/order/'+
              scope.row.id
            "
          >
          <el-button
            type="success"
            icon="el-icon-view"
            circle
          ></el-button>
          </router-link>
          
        </template>
      </el-table-column>
    </el-table>
    <el-dialog :title="'Create new Order'" :visible.sync="dialogFormVisible">
      <div v-loading="categoryCreating" class="form-container">
        <el-form
          ref="categoryForm"
          :rules="rules"
          :model="cart"
          label-position="left"
          label-width="150px"
          style="max-width: 500px"
        >
          <el-form-item label="Product Id" prop="product" required="">
            <el-select v-model="cart.product" 
            filterable 
            placeholder="Select"
            >
                <el-option
                  v-for="product in productList"
                  :key="product.id"
                  :label="product.name"
                  :value="product">
                </el-option>
              </el-select>
          </el-form-item>
          <el-form-item label="Quantity" prop="quantity" required="">
            <el-input v-model="cart.quantity" />
          </el-form-item>
          <p v-if="product != null">{{product.price * cart.quantity}}</p>
          <el-form-item label="Discount" prop="discount" required="">
            <el-input v-model="cart.discount" />
          </el-form-item>
          <el-form-item label="Paid" prop="paid" required="">
            <el-input v-model="cart.paid" />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="close">{{ $t('table.cancel') }}</el-button>
          <el-button type="primary" @click="addToCart">
            {{ $t('table.confirm') }}
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
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import waves from '@/directive/waves';
import permission from '@/directive/permission';

const orderResource = new Resource('orders');
export default {
  name: 'ProductList',
  components: { Pagination },
  directives: { waves, permission },
  data() {
    return {
      type: '',
      list: null,
      dialogFormVisible: false,
      categoryCreating: false,
      cart:{},
      product: {},
      cartTotal: 0,
      productList: [],
      total: 0,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
      },
      imageUrl: null,
      rules: {
        code: [
          {
            required: true,
            message: 'Code is required',
            trigger: blur,
          },
        ],
      },
    };
  },
  created() {
    this.getList();
    this.getProductList();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await orderResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.loading = false;
    },
    async getProductList() {
      axios.get('/api/all-products').then((response) => {
        this.productList = response.data;
      });
    },
    handleCreate(){
      this.type = 'create';
      this.order = {};
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['categoryForm'].clearValidate();
      });
    },
    close() {
      this.dialogFormVisible = false;
      this.order = {};
    },
    handleSelectedProduct(product){
      console.log('product is ', product);
      this.product = product;
    },
    addToCart(){
      console.log('orders ', this.cart);
      this.cart.total = (parseInt(this.cart.quantity) * parseInt(this.cart.product.price) - this.cart.discount);
      console.log(this.cart);
      this.$store.dispatch('cart/addToCart', this.cart).then(() => {
        this.getCartTotal();
        this.cart = {};
        this.dialogFormVisible = false;
      });
    },
    getCartTotal(){
      this.cartTotal = this.$store.getters.carts.length;
    },
    handleUpdate(data){
      this.order = data;
      this.type = 'edit';
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['categoryForm'].clearValidate();
      });
    },
    handleDelete(id) {
      this.$confirm(
        'This will permanently delete Order ' + name + '. Continue?',
        'Warning',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
        }
      )
        .then(() => {
          orderResource.destroy(id)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Delete completed',
              });
              this.getList();
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Delete canceled',
          });
        });
    },
    handleFilter() {
      this.getList();
    },
    goToCart(){
      this.$router.push('/order/place');
    }
  },
};
</script>

<style>
.dialog-footer {
  margin-top: 80px;
}
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>