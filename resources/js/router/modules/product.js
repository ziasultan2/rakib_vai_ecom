/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const productRoutes = {
  path: '/product',
  component: Layout,
  name: 'Product',
  meta: {
    permissions: [],
  },
  children: [
    {
        path: 'all',
        component: () => import('@/views/product/index'),
        name: 'Product',
        meta: { title: 'Product', icon: 'el-icon-s-shop', noCache: true },
    },
  ],
};

export default productRoutes;