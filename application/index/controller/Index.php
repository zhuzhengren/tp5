<?php

namespace app\index\controller;

use app\common\controller\Base;
use app\common\model\ArticleCate;
use app\common\model\Article;
use think\facade\Request;
use think\Db;

class Index extends Base {

    public function index() {


        //全局查询条件
        $map = [];

        //条件一
        $map[] = ['status', '=', 1];

        //实现搜索功能
        $keywords = Request::param('keywords');

        if (!empty($keywords)) {
            //条件2
            $map[] = ['title', 'like', '%' . $keywords . '%'];
        }

        //分类信息显示
        $cateId = Request::param('cate_id');
        //如果存在分类ID
        if (isset($cateId)) {
            //条件3
            $map[] = ['cate_id', '=', $cateId];
            //查询
            $res = ArticleCate::get($cateId);

            $artList = Db::table('zh_article')
                    ->where($map)
                    ->order('create_time', 'desc')
                    ->paginate(3);

            $this->view->assign('cateName', $res->name);
        } else {
            $artList = Db::table('zh_article')
                    ->where($map)
                    ->order('create_time', 'desc')
                    ->paginate(3);
            $this->view->assign('cateName', "全部文章");
        }
        $this->view->assign('empty', '<h3>没有文章</h3>');

        $this->view->assign('artList', $artList);

        return $this->view->fetch();
    }

    //添加文章界面
    public function insert() {
        /**
         * 1.登陆才允许发布文章
         * 2。设置页面标题
         * 3。获取栏目信息
         * 4。发布界面预览
         */
        $this->isLogin();
        $this->view->assign('title', '发布文章');
        $catelist = ArticleCate::all();
        //halt($catelist);
        if (count($catelist) > 0) {
            //将查询到的栏目信息复制给模板变量
            $this->assign('cateList', $catelist);
        } else {
            $this->error('请添加栏目', 'index/index');
        }
        return $this->view->fetch('insert');
    }

    //保存文章
    public function save() {
        //判断提交类型
        if (Request::isPost()) {
            //获取用户提交的文章信息
            $data = Request::post();
            // halt($data);
            $res = $this->validate($data, 'app\common\validate\Article');
            if (true !== $res) {
                echo '<script>alert("' . $res . '");location.back();</script>';
            } else {
                //验证成功
                //获取一下图片的信息
                $file = request()->File('title_img');
                //文件信息验证成功后，在上传到服务器指定的目录，以public为起始目录
                $info = $file->validate([
                            'size' => 1024 * 1024,
                            'ext' => 'jpeg,jpg,png,git',
                        ])->move("uploads");

                if ($info) {
                    //上传成功
                    //用户上传到服务器之后的名字
                    //getSaveName()全路径 getFileName()文件名

                    $data['title_img'] = $info->getSaveName();
                    //    echo '<script>alert("' . $info->getFileName() . '");location.back();</script>';
                } else {
                    $this->error($file->getError(), 'index/insert');
                }

                if (Article::create($data)) {
                    $this->success("文章发布成功", 'index/index');
                } else {
                    $this->error("文章保存失败");
                }
            }
        } else {
            $this->error('请求类型错误', 'index/index');
        }
    }

    //详情页
    public function detail() {
        $artId = Request::param('artid');
        $art = Article::get(function ($query)use ($artId) {
                    $query->where('id', '=', $artId)->setInc('pv');
                });
        if(!is_null($art)){
            $this->view->assign('art',$art);
        }
        $this->view->assign('title', '详情页');
        return $this->view->fetch('detail');
    }

}
