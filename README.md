# TeebbUploaderBundle
《Symfony Bundle开发》视频教程中的课程代码。`master`分支中的代码将会删除不必要的注释继续完善功能。
`tutorial`分支保留视频课程里的步骤代码用于Bundle开发的学习。

### 《Symfony Bundle开发》视频教程地址：
|  网站   | 视频播放URL | 字幕 |
|  ----  | ---- | ---- |
| teebb  | https://www.teebb.com/course-detail/symfony-bundle-development | 有字幕 |
| bilibili | https://www.bilibili.com/video/BV1Dq4y1x7tm | 有字幕 |

### 功能介绍
减少Symfony项目中的文件上传复杂度。提供文件上传表单，文件上传后自动使用Entity类封装文件数据，并在插入数据库时完成文件上传。

支持多个文件Entity类，支持不同的文件Entity类上传到不同的文件目录，支持不同的文件Entity类使用不同的文件命名规则。详细原理，请查看视频教程。

### 安装
目前Bundle功能会继续开发，请安装master分支的代码。
```
composer require teebbstudios/uploader-bundle:dev-master
```

### 配置模板

```yaml
#<symfony-project-root>/config/packages/teebb_uploader.yaml

teebb_uploader:
    handlers:
        # 为不同的文件Entity类定义不同的Handler， 键值用于handler服务类的id，可以任意定义
        # 使用FileSystem上传到本地服务器
        simple_file_handler:
            entity: App\Entity\SimpleFile # 文件数据的Entity全类名
            upload_dir: '%kernel.project_dir%/public/teebb_upload'  # 上传的目标目录
            package_name: 'teebb_upload' # 用于文件显示时添加前缀，需要在assets.yaml文件中进行配置
        
        # 使用FlySystem上传文件
        simple_file_handler_demo2:
            entity: App\Entity\SimpleFile # 文件数据的Entity全类名
            upload_dir: 'string'  # 上传的目标目录
            package_name: 'string' # 用于文件显示时添加前缀，需要在assets.yaml文件中进行配置
            storage:  
                type: 'fly_system' # 使用FlySystem完成文件的上传，需要安装oneup/flysystem-bundle
                service: 'oneup_flysystem.default_filesystem_filesystem' # 可使用flysystem提供的服务类id 或 配置项名称，例如："default_filesystem"
            namer:
                service: Teebb\UploadBundle\Namer\HashNamer
                options:
                    algorithm: sha1
                    length: 10

```
### 代码协议
开源项目，本项目代码遵循MIT开源协议，欢迎贡献代码。

### 广告
在企业中Symfony项目是按Bundle分模块开发的，学习Bundle开发很有必要。本视频教程为本人原创教程，花费了大量精力，保证一如既往的视频质量。
如有需求请 付费支持一下，[《Symfony Bundle开发视频教程》](https://www.teebb.com/course-detail/symfony-bundle-development) 。

有回馈我才有动力继续制作下一套视频教程，谢谢！！！🤝🏻

承接Symfony、Magento项目外包，公司资质，可签合同，联系微信：443580003