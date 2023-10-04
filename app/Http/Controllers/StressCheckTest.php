<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StressCheckTest extends Controller
{
    public function checktest_post(Request $request)
        {
            $user_id = (int)Auth::id();
            $checktest_id = $request->checktest_id;
    
            $problems = CheckTestProblem::where(['checktest_id' => $checktest_id])->get(['id','content']);
    
            $number_of_right_answers = 0; //正答数
            $number_of_problems = 0;      //問題数
    
            //理解度テスト結果表示のためのデータ作成
            foreach($problems as $problem){
                $answer = CheckTestAnswer::where(['id' => $request->item[$problem->id]])->first(['answer']);
                $correct_answer = CheckTestAnswer::where(['checktest_problem_id' => $problem->id, 'correct_flg' => 1])->first(['id','answer']);
                $data[$problem->content][0] = [$problem->id,$request->item[$problem->id],$answer->answer];
                $data[$problem->content][1] = [$problem->id,$correct_answer->id,$correct_answer->answer];
                
                // 正答数を計算
                if ($request->item[$problem->id] == $correct_answer->id) $number_of_right_answers++;
                $number_of_problems++;
            }
    
            //生成されるdataのイメージ
            //$data = [
            //    '問題文' => [[問題番号,選択した回答番号,選択した回答文],[問題番号,正解の回答番号,正答文]]
            //    '問題1' => [[1,1,'回答1_1'],[problem1,2,'回答1_2']],
            //    '問題2' => [[problem2,5,'回答2_1'],[problem2,6,'回答2_2']]
            //];
    
            // 初めての理解度テスト受験時は受験結果を追加する
            $test = "none";
            if (CheckTestProgression::where(['user_id' => $user_id, 'check_test_id' => $checktest_id])->count() == 0) {
                $checktestprogression = new CheckTestProgression;
                $checktestprogression->fill(['user_id' => $user_id, 'check_test_id' => $checktest_id]);
                $checktestprogression->save();
                $test = "create!";
            }
    
            $checktestprogression = CheckTestProgression::where(['user_id' => $user_id, 'check_test_id' => $checktest_id]);
    
            // 現状の点数 > 今回の点数の場合、更新する
            if ($number_of_right_answers > $checktestprogression->first()->number_of_right_answers) {
                $checktestprogression->update(['number_of_right_answers' => (int)$number_of_right_answers, 'number_of_problems' => (int)$number_of_problems]);
            }
    
            return view('checktest.checktestresult', ['data' => $data, 'number_of_right_answers' => $number_of_right_answers, 'number_of_problems' => $number_of_problems]);
        }
}