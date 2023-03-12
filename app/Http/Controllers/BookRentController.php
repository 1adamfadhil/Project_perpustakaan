<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use App\Models\RentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BookRentController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 2)->where('status', '!=', 'inactive')->get();
        $books = Book::all();
        return view('book-rent', ['users' => $users, 'books' => $books]);
    }
    
    public function store(Request $request)
    {
        $request['rent_date'] = Carbon::now()->toDateString();
        $request['return_date'] = Carbon::now()->addDay(5)->toDateString();
        
        $book = Book::findOrFail($request->book_id)->only('status');

        if($book['status'] != 'in stock') {
            Session::flash('message', 'Cannot rent, the book is not available');
            Session::flash('alert-class', 'alert-danger');
            return redirect('book-rent');
        }
        else {
            $count = RentLogs::where('user_id', $request->user_id)->where('actual_return_date', null)->count();
           
            if($count >= 3) {
                Session::flash('message', 'Cannot rent, user has reach limit of books');
            Session::flash('alert-class', 'alert-danger');
            return redirect('book-rent');
            }
            else {
                try {
                    DB::beginTransaction();
                    // process insert to rent_logs table
                    RentLogs::create($request->all());
                    // process update book table
                    $book = Book::findOrFail($request->book_id);
                    $book->status = 'not available';
                    $book->save();
                    DB::commit();
    
                    Session::flash('message', 'Rent book success!!!');
                    Session::flash('alert-class', 'alert-success');
                    return redirect('book-rent');
                } catch (\Throwable $th) {
                    DB::rollBack();
                }
            }
        }
    }

    public function returnBook()
    {
        $users = User::where('role_id', 2)->where('status', '!=', 'inactive')->get();
        $books = Book::all();
        return view('return-book', ['users' => $users, 'books' => $books]);
    }

    public function saveReturnBook(Request $request)
    {
        $rent = RentLogs::where('user_id', $request->user_id)->where('book_id', $request->book_id)->where('actual_return_date', null);
        $rentData = $rent->first();
        $countData = $rent->count();
        
        if($countData == 1) {
            $rentData['actual_return_date'] = Carbon::now()->toDateString();
            $rentData->save();

            Session::flash('message', 'The Book is returned successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('book-return');
        }
        else {

        }
    }
}
