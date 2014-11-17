<!DOCTYPE html>
<!--
    Created on : May 3, 2014, 6:10:01 PM
    Author     : mzijlstra 
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Flow Chart</title>
        <link rel="stylesheet" href="page.css" type="text/css" />
        <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
        <!-- <script src="http://code.jquery.com/jquery-2.1.1.js"></script> -->
        <script src="jquery-2.1.1.js" ></script>
        <script src="wr.js"></script>
    </head>
    <body>
        <h1>My Project</h1>
        <div id="projects">
            <div class="arrow_down"></div>
            <div id="project_menu" class="menu">
                <div class="menu_item">New Project</div>
                <div class="menu_item">Open Project</div>
                <?php if ($_SESSION['recent']) : ?>
                    <div class="menu_label">----Recent----</div>
                    <?php foreach ($_SESSION['recent'] as $recent) : ?>
                        <div class="menu_item"><?= $recent ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="user">Hi <?= $_SESSION['user']['first'] ?>! <a href="logout">logout</a></div>

        <div id="workspace">
            <div id="var_area">
                <div id="variables">
                    <div id="vars_main" class="variables active">
                        <div class="label">Variables:</div>
                        <div class="variable bottom">
                            <div class="del">&times;</div>
                            <div class="type_container">
                                <div class="menu">
                                    <div class="menu_item">string</div>
                                    <div class="menu_item">int</div>
                                    <div class="menu_item">float</div>
                                    <div class="menu_item">bool</div>
                                    <div class="menu_split"></div>
                                    <div class="menu_item">string[]</div>
                                    <div class="menu_item">int[]</div>
                                    <div class="menu_item">float[]</div>
                                    <div class="menu_item">bool[]</div>
                                    <div class="menu_split"></div>
                                    <div class="menu_edit">edit</div>
                                </div>
                                <span class="type">string</span>
                            </div>
                            <input class="var" autofocus pattern="[_a-zA-Z]([_0-9a-zA-Z]+)?"/>
                        </div>
                    </div>
                </div>
            </div>

            <div id="functions">
                <div id="fun-names">
                    <span id="add_fun">+</span>
                    <span class="fun active">
                        <span class="cornerb">
                            <span class="cornerw"></span>
                        </span>
                        <span class="name">main</span>
                        <span class="rem">&times;</span>
                    </span>
                </div>
                <div id="instructions">
                    <div id="ins_main" class="instructions active">
                        <div class="statement">
                            <div class="start">
                                <div class="text">
                                    <span>int</span>
                                    <span class="name">main</span>(<span class="params"></span>)
                                </div>
                            </div>
                        </div>
                        <div class="connection">
                            <div class="line"></div>
                            <div class="point"></div>            
                            <div class="line"></div>
                        </div>
                        <div class="statement return">
                            <div class="arrow_down"></div>                        
                            <div class="stop">
                                <div class="text">
                                    return <span class="exp">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- end workspace -->
        <footer>Web-Raptor</footer>

        <div id="ins_menu" class="menu">
            <div class="menu_item" id="add_input">Input</div>
            <div class="menu_item" id="add_output">Output</div>
            <div class="menu_item" id="add_assignment">Assignment</div>
            <div class="menu_item" id="add_if">If Statement</div>
            <div class="menu_item" id="add_while">While Loop</div>
        </div>


        <div id="building_blocks">
            <span id="fun-name" class="fun">
                <span class="cornerb">
                    <span class="cornerw"></span>
                </span>
                <span class="name">extra</span>
                <span class="rem">&times;</span>
            </span>

            <div id="declaration" class="variable bottom">
                <div class="del">&times;</div>
                <div class="type_container">
                    <div class="menu">
                        <div class="menu_item">string</div>
                        <div class="menu_item">int</div>
                        <div class="menu_item">float</div>
                        <div class="menu_item">bool</div>
                        <div class="menu_split"></div>
                        <div class="menu_item">string[]</div>
                        <div class="menu_item">int[]</div>
                        <div class="menu_item">float[]</div>
                        <div class="menu_item">bool[]</div>
                        <div class="menu_split"></div>
                        <div class="menu_edit">edit</div>
                    </div>
                    <span class="type">string</span>
                </div>
                <input class="var" pattern="[_a-zA-Z]([_0-9a-zA-Z]+)?"/>
            </div>

            <div id="start" class="statement">
                <div class="start">
                    <div class="text">
                        <div class="type_container">
                            <div class="menu">
                                <div class="menu_item">string</div>
                                <div class="menu_item">int</div>
                                <div class="menu_item">float</div>
                                <div class="menu_item">bool</div>
                                <div class="menu_split"></div>
                                <div class="menu_item">string[]</div>
                                <div class="menu_item">int[]</div>
                                <div class="menu_item">float[]</div>
                                <div class="menu_item">bool[]</div>
                                <div class="menu_split"></div>
                                <div class="menu_edit">edit</div>
                            </div>
                            <span class="type">int</span>
                        </div>
                        <span class="name">main</span>(<span class="params"></span>)
                    </div>
                </div>
            </div>

            <div id="return" class="statement">
                <div class="arrow_down"></div>                        
                <div class="stop">
                    <div class="text">
                        return <span class="exp">0</span>
                    </div>
                </div>
            </div>

            <div id="connection" class="connection">
                <div class="line"></div>
                <div class="point"></div>            
                <div class="line"></div>
            </div>

            <div id="input" class="statement" >
                <div class="del">&times;</div>
                <div class="arrow_down"></div>                        
                <div class="assignment">
                    <div class="var_container">
                        <div class="menu"></div>
                        <span class="var">&nbsp;</span>
                    </div>
                    &nbsp;<span class="asgn">&#171;</span> 
                    <span class="io">INPUT</span>
                </div>
            </div>

            <div id="output" class="statement" >
                <div class="del">&times;</div>
                <div class="arrow_down"></div>                        
                <div class="assignment">
                    <span class="io">OUTPUT</span>
                    <span class="asgn">&#171;</span> 
                    <span class="exp">&nbsp;</span>
                </div>
            </div>

            <div id="assignment" class="statement" >
                <div class="del">&times;</div>
                <div class="arrow_down"></div>                        
                <div class="assignment">
                    <div class="var_container">
                        <div class="menu"></div>
                        <span class="var">&nbsp;</span>
                    </div>
                    &nbsp;<span class="asgn">&#171;</span> 
                    <span class="exp">&nbsp;</span>
                </div>
            </div>

            <div id="if" class="statement" >
                <div class="arrow_down"></div>                        
                <div class="if">
                    <div class="diamond">
                        <div class="del">&times;</div>
                        <div class="diamond_top_outside">
                            <div class="diamond_top_inside">
                            </div>
                        </div>
                        <div class="diamond_bot_outside">
                            <div class="diamond_bot_inside">
                            </div>
                        </div>
                        <div class="label_stmt">if</div>
                        <div class="label_true">true</div>
                        <div class="label_false">false</div>
                        <div class="exp"></div>
                    </div>
                    <table>
                        <tr>
                            <td class="left">
                                <div class="top_connect"></div>
                                <div class="absolute_left"></div>

                                <div class="connection">
                                    <div class="line"></div>
                                    <div class="point"></div>            
                                    <div class="line"></div>
                                </div>

                                <div class="bot_left_connect">
                                    <div class="arrow_left"></div>
                                </div>
                            </td>
                            <td class="right">
                                <div class="absolute_right"></div>

                                <div class="connection">
                                    <div class="line"></div>
                                    <div class="point"></div>            
                                    <div class="line"></div>
                                </div>

                                <div class="bot_right_connect"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="while" class="statement">
                <div class="arrow_down"></div>                        
                <div class="while">
                    <div class="diamond">
                        <div class="del">&times;</div>
                        <div class="diamond_top_outside">
                            <div class="diamond_top_inside">
                            </div>
                        </div>
                        <div class="diamond_bot_outside">
                            <div class="diamond_bot_inside">
                            </div>
                        </div>
                        <div class="label_stmt">while</div>
                        <div class="label_true">true</div>
                        <div class="label_false">false</div>
                        <div class="exp"></div>
                        <div class="true_connector"></div>
                    </div> 
                    <div class="container">
                        <div class="false_line"></div>
                        <div class="return_line">
                            <div class="arrow_up"></div>
                        </div>
                        <div class="loop_body">
                            <div class="connection">
                                <div class="line"></div>
                                <div class="point"></div>            
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end building blocks -->

    </body>
</html>