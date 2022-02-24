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
    </div>
    <el-table v-loading="loading" :data="list" style="width: 100%">
      <el-table-column prop="id" label="Id" width="60" />
      <el-table-column prop="customer_name" label="Name" width="150" />
      <el-table-column prop="phone" label="Phone" width="150" />
      <el-table-column prop="product_id" label="Product Id" width="100" />
      <el-table-column prop="quantity" label="Quantity" width="100" />
      <el-table-column prop="price" label="Price" width="100" />
      <el-table-column prop="discount" label="Discount" width="100" />
      <el-table-column prop="total" label="Total" width="100" />
      <el-table-column prop="address" label="Address" width="100" />
      <!-- <el-table-column label="Operations" width="200">
        <template slot-scope="scope">
          <el-button
            type="danger"
            icon="el-icon-delete"
            @click="handleDelete(scope.row.id)"
            circle
          ></el-button>

        </template>
      </el-table-column> -->
    </el-table>
    <el-dialog :title="'Create new Order'" :visible.sync="dialogFormVisible">
      <div v-loading="categoryCreating" class="form-container">
        <el-form
          ref="categoryForm"
          :rules="rules"
          :model="order"
          label-position="left"
          label-width="150px"
          style="max-width: 500px"
        >
          <el-form-item label="Name" prop="customer_name" required="">
            <el-input v-model="order.customer_name" />
          </el-form-item>
          <el-form-item label="Phone" prop="phone" required="">
            <el-input v-model="order.phone" />
          </el-form-item>
          <el-form-item label="Product Id" prop="product_id" required="">
            <el-input v-model="order.product_id" />
          </el-form-item>
          <el-form-item label="Price" prop="price" required="">
            <el-input v-model="order.price" />
          </el-form-item>
          <el-form-item label="Quantity" prop="quantity" required="">
            <el-input v-model="order.quantity" />
          </el-form-item>
          <el-form-item label="Discount" prop="discount" required="">
            <el-input v-model="order.discount" />
          </el-form-item>
          <el-form-item label="Total" prop="total" required="">
            <el-input v-model="order.total" />
          </el-form-item>
          <el-form-item label="Delivery Address" prop="address" required="">
            <el-input v-model="order.address" />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="close">{{ $t('table.cancel') }}</el-button>
          <el-button v-if="type === 'create'" type="primary" @click="createProduct">
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
      order:{},
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
    createProduct(){
      axios.post('/api/orders', this.order).then((response) => {
        this.$message({
              message: 'Order created successfully',
              type: 'success',
              duration: 5 * 1000,
            });
            this.getList();
            this.close();
            
      });
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