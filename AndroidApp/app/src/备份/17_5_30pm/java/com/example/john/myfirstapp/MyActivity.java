package com.example.john.myfirstapp;
import android.content.Intent;
import android.view.View;
import android.widget.EditText;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Spinner;
import android.widget.TextView;


public class MyActivity extends ActionBarActivity {
    public final static String EXTRA_MESSAGE = "com.mycompany.myfirstapp.MESSAGE";

    public static boolean isNumeric(String str)
    {
        try
        {
            double d = Double.parseDouble(str);
        }
        catch(NumberFormatException nfe)
        {
            return false;
        }
        return true;
    }
    public void clear_f(View view){
        EditText keyword = ( EditText )findViewById(R.id.keyword_blank);
        EditText price_from = (EditText)findViewById(R.id.price_from_blank);
        EditText price_to = (EditText)findViewById(R.id.price_to_blank);
        Spinner sort_by = (Spinner)findViewById(R.id.sort_by_blank);

        keyword.setText("");
        price_from.setText("");
        price_to.setText("");
        sort_by.setSelection(0);
        //findViewById(R.id.sort_by_blank);
    }
    public void validate_f(View view){
        EditText keyword = ( EditText )findViewById(R.id.keyword_blank);
        EditText price_from = (EditText)findViewById(R.id.price_from_blank);
        EditText price_to = (EditText)findViewById(R.id.price_to_blank);
        Spinner sort_by = (Spinner)findViewById(R.id.sort_by_blank);
        TextView show_validation =(TextView)findViewById(R.id.show_validation);
        String keyword_str = keyword.getText().toString();
        String from_str  = price_from.getText().toString();
        String to_str = price_to.getText().toString();
        String sortby_str = sort_by.getSelectedItem().toString();

        int validate_flag =1;
        //int from_int = Integer.parseInt( from_str );
        //int to_int = Integer.parseInt( to_str );

        if( !from_str.equals("") ){
            if(isNumeric(from_str)){
                show_validation.setText("");
                int from_int = Integer.parseInt( from_str );
                if(from_int >=0){
                    show_validation.setText("");
                    if(isNumeric(to_str)){
                        show_validation.setText("");
                        int to_int = Integer.parseInt( to_str );
                        if( from_int<=to_int){
                            show_validation.setText("");
                        }
                        else{
                            show_validation.setText("price to should be greater than price from");
                            validate_flag = 0;
                        }
                    }
                }
                else{
                    show_validation.setText("price from should be greater than or equal to 0");
                    validate_flag = 0;
                }
            }
            else{
                show_validation.setText("Price from should be a Number");
                validate_flag =0;
            }
        }
        if(!to_str.equals("") && validate_flag == 1){
            if(isNumeric(to_str)){
                show_validation.setText("");
                int to_int = Integer.parseInt( to_str );
                if(to_int >=0){
                    show_validation.setText("");
                }
                else{
                    show_validation.setText("price to should be greater than or equal to 0");
                }
            }
            else{
                show_validation.setText("Price to should be a Number");
                validate_flag =0;
            }
        }
        if( keyword.getText().toString().matches("") ){
            show_validation.setText("Please enter a keyword");
            validate_flag = 0;
        }
        else{
            if(validate_flag == 1)
                show_validation.setText("");
        }
    }

/*
    public void sendMessage(View view) {
        Intent intent = new Intent(this,DisplayMessageActivity.class);
        EditText editText = (EditText) findViewById(R.id.edit_message);
        String message = editText.getText().toString();
        intent.putExtra(EXTRA_MESSAGE, message);
        startActivity(intent);
    }
*/
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_my);
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_my, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }
}
