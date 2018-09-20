<?php

namespace app\index\controller;

use app\common\controller\Base;
use app\common\model\ArticleCate;
use app\common\model\Article;
use app\common\model\Comment;
use think\facade\Request;
use think\Db;
use think\facade\Session;

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
            //点击分页查询按钮时额外的参数，防止点击搜索结果第二页参数消失的问题
            $pagQuery = ['keywords' => $keywords];
        } else {
            $pagQuery = [];
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
                    ->paginate(3, false, [
                'query' => $pagQuery,
                'type' => 'bootstrap',
                'var_page' => 'artList',
            ]);

            $this->view->assign('cateName', $res->name);
        } else {
            $artList = Db::table('zh_article')
                    ->where($map)
                    ->order('create_time', 'desc')
                    ->paginate(3, false, [
                'query' => $pagQuery,
                'type' => 'bootstrap',
                'var_page' => 'artList',
            ]);
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
        if (!is_null($art)) {
            $this->view->assign('art', $art);
        }
        $this->view->assign('title', '详情页');

        //同步收藏状态和点赞状态
        //用户未登陆默认显示未收藏和点赞
        if (!Session::has('user_id')) {
            $this->view->assign('like', 0);
            $this->view->assign('fav', 0);
        } else {
            //数据库查询条件
            $map[] = ['user_id', '=', Session::get('user_id')];
            $map[] = ['art_id', '=', $artId];
            $fav = Db::table('zh_user_collect')->where($map)->find();
            //判断是否收藏
            if (is_null($fav)) {
                $this->view->assign('fav', 0);
            } else {
                $this->view->assign('fav', 1);
            }
            //判断是否点赞
            $like = Db::table('zh_user_like')->where($map)->find();
            if (is_null($like)) {
                $this->view->assign('like', 0);
            } else {
                $this->view->assign('like', 1);
            }
        }
        $commentList = Comment::all(function($query) use ($artId) {
                    $query->where('status', 1)
                            ->where('art_id', $artId)
                            ->order('create_time', 'desc');
                });

        //  添加评论
        $this->assign('commentList', $commentList);



        return $this->view->fetch('detail');
    }

    //收藏
    public function fav() {
        //请求方式必须正确
        if (!Request::isAjax()) {
            return ['status' => -1, 'message' => '请求方式错误'];
        }
        $data = Request::param();
        //用户未登陆不允许操作
        //halt($data);
        if (empty($data['session_id'])) {
            // halt($data);
            return ['status' => -2, 'message' => '用户未登陆'];
        }
        //数据库查询条件
        $map[] = ['user_id', '=', $data['session_id']];
        $map[] = ['art_id', '=', $data['art_id']];
        //查询是否存在收藏记录
        $fav = Db::table('zh_user_collect')->where($map)->find();
        if (is_null($fav)) {
            //未收藏 点击收藏
            Db::table('zh_user_collect')->data([
                'user_id' => $data['session_id'],
                'art_id' => $data['art_id'],
            ])->insert();
            //halt($data);
            return ['status' => 1, 'message' => '收藏成功'];
        } else {
            //已收藏 取消收藏
            Db::table('zh_user_collect')->where($map)->delete();
            return ['status' => 0, 'message' => '已取消收藏'];
        }
    }

    //点赞
    public function like() {
        //请求方式必须正确
        if (!Request::isAjax()) {
            return ['status' => -1, 'message' => '请求方式错误'];
        }
        $data = Request::param();
        //用户未登陆不允许操作
        //halt($data);
        if (empty($data['session_id'])) {
            // halt($data);
            return ['status' => -2, 'message' => '用户未登陆'];
        }
        //数据库查询条件
        $map[] = ['user_id', '=', $data['session_id']];
        $map[] = ['art_id', '=', $data['art_id']];
        //查询是否存在收藏记录
        $fav = Db::table('zh_user_like')->where($map)->find();
        if (is_null($fav)) {
            //未收藏 点击收藏
            Db::table('zh_user_like')->data([
                'user_id' => $data['session_id'],
                'art_id' => $data['art_id'],
            ])->insert();
            //halt($data);
            return ['status' => 1, 'message' => '点赞成功'];
        } else {
            //已收藏 取消收藏
            Db::table('zh_user_like')->where($map)->delete();
            return ['status' => 0, 'message' => '已取消点赞'];
        }
    }

    public function insertComment() {

        if (Request::isAjax()) {
            $data = Request::param();
            //  halt($data);
            if (Comment::create($data, true)) {
                return ['status' => 1, 'message' => '发表成功'];
            } else {
                return ['status' => -1, 'message' => '发表失败'];
            }
        }
    }

}
