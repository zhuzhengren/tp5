<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\common\model\ArticleCate;
use app\common\model\Article;
use think\facade\Request;

class Index extends Base
{
    public function index()
    {
        
        return $this->view->fetch();
    }

    //添加文章界面
    public function insert()
    {
        /**
         * 1.登陆才允许发布文章
         * 2。设置页面标题
         * 3。获取栏目信息
         * 4。发布界面预览
         */
        $this->isLogin();
        $this->view->assign('title','发布文章');
        $catelist = ArticleCate::all();
        //halt($catelist);
        if(count($catelist) > 0){
            //将查询到的栏目信息复制给模板变量
            $this->assign('cateList',$catelist);
            
        }else{
            $this->error('请添加栏目','index/index');
        }
        return $this->view->fetch('insert');
    }


    //保存文章
    public function save()
    {
        //判断提交类型
        if(Request::isPost()){
            //获取用户提交的文章信息
            $data = Request::post();
           // halt($data);
            $res = $this->validate($data, 'app\common\validate\Article');
            if(true!==$res){
                echo '<script>alert("'.$res.'");location.back();</script>';
            }else{
                //验证成功
                //获取一下图片的信息
                $file = request()->File('title_img');
                //文件信息验证成功后，在上传到服务器指定的目录，以public为起始目录
                $info = $file->validate([
                    'size'=>1024*1024,
                    'ext'=>'jpeg,jpg,png,git',
                ])->move(':url("/public/uploads")');
                
                if($info){
                    //上传成功
                    //用户上传到服务器之后的名字
                    //getSaveName()全路径 getFileName()文件名
                    
                    $data['title_img'] = $info->getSaveName();
                    echo '<script>alert("'.$info->getFileName().'");location.back();</script>';
                }else{
                    $this->error($file->getError(),'index/insert');
                }
                
                if(Article::create($data)){
                    $this->success("文章发布成功",'index/index');
                }else{
                    $this->error("文章保存失败");
                }
            }
            
        }else{
            $this->error('请求类型错误','index/index');
        }
    }

}
