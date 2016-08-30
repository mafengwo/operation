<div class="info-box">
    <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>
    <div class="info-box-content">
        <span class="info-box-number">Welcome!</span>
        <span class="info-box-text"><p>欢迎使用蚂蜂窝自动化运维管理系统，请从左侧菜单中选择要操作的内容。</p></span>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">About Me</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body" style="display: block;">
        <div class="box-group" id="accordion">
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="">
                        这是什么
                      </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true">
                    <div class="box-body">
                        <p>AOS: Automatic Operation System 自动化运维系统。</p>
                        <p>旨在开发一套可以帮助运维同学工作的系统。以图形化界面和批量操作为核心，使服务器端的各类部署和维护工作可以定制操作，一键运行，并可追溯。
                          除此外还可以针对用户授权不同的操作，方便将任务的操作权限下放到开发人员手中。</p>
                        <p>同时一个用户交互友好的系统，可以很快上手并且高效的完成其他任务，这让编写个性化系统或者接入API到同一个操作平台成为了可能。</p>
                    </div>
                </div>
            </div>
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">
                        开发环境和技术细节
                      </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                        <p>该系统采用LNMP为开发环境，所有功能均以模块式开发，模块间采用低耦合。下面列表为技术细节：</p>
                        <ul>
                          <li>PHP Version: 7.0 </li>
                          <li>PHP Frameworks: <a href="https://github.com/zhangchu/ko" target="_blank" title="基于MVC的三层框架">KO</a></li>
                          <li>MySQL 连接方式: PDO </li>
                          <li>HTML&CSS&JS Frameworks: <a href="https://github.com/almasaeed2010/AdminLTE" target="_blank">AdminLTE</a> (base on <a href="
                            https://github.com/twbs/bootstrap" target="_blank">Bootstrap 3</a>)</li>
                          <li>远程管理 (如不需要远程管理功能则无需此项)
                            <ul>
                              <li> <a href="https://github.com/ansible/ansible" target="_blank">Ansible 2.2.0</a> (Python > 2.6 best 2.7.5) </li>
                              <li> <a href="https://github.com/lfbear/ansible-api" target="_blank">Ansible API</a> (A restful http api for ansible)</li>
                            </ul>
                          </li>
                        </ul>
                        <p>目录结构</p>
                        <ul>
                          <li>ko <span class="text-yellow">ko框架</span></li>
                          <li>
                            www <span class="text-yellow">项目代码</span>
                            <ul>
                              <li>apps <span class="text-yellow">模块目录(module层)</span>
                                <ul>
                                  <li>ansible <span class="text-yellow">远程控制</span></li>
                                  <li>operation <span class="text-yellow">本项目</span>
                                      <ul>
                                        <li>htdocs <span class="text-yellow">controller层代码</span></li>
                                        <li>templates <span class="text-yellow">模板目录</span></li>
                                        <li>Conf.php <span class="text-yellow">业务配置</span></li>
                                        <li>Rest.php <span class="text-yellow">restful支持</span></li>
                                        <li>rewrite.txt <span class="text-yellow">rewrite配置</span></li>
                                      </ul>
                                  </li>
                                  <li>parameter <span class="text-yellow">配置模块</span></li>
                                  <li>render <span class="text-yellow">view业务层(对smarty的业务包装)</span></li>
                                  <li>server <span class="text-yellow">服务器功能</span></li>
                                  <li>sqlite <span class="text-yellow">sqlite扩展</span></li>
                                  <li>system <span class="text-yellow">核心模块(节点,用户,权限管理,登录,注册等)</span></li>
                                </ul>
                              </li>
                              <li>conf <span class="text-yellow">配置文件(vhost等)</span></li>
                              <li>libs <span class="text-yellow">第三方库(smarty等)</span></li>
                              <li>rundata <span class="text-yellow">运行时目录(要求可写)</span></li>
                              <li>static <span class="text-yellow">静态资源</span>
                                <ul>
                                  <li>css</li>
                                  <li>fonts</li>
                                  <li>img</li>
                                  <li>js</li>
                                </ul>
                              </li>
                            </ul>
                          </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">
                        版权和作者
                      </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                        <p>本程序遵循 <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License V2.0</a> 协议，
                          这意味着您可以免费使用，或进行二次开发并进行再发布。</p>
                        <p>作者 <a href="https://github.com/lfbear/" target="_blank">lfbear</a> & <a herf="#">jichen chou</a> works at <a href="http://www.mafengwo.cn" target="_blank">Mafengwo.CN</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
