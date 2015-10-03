package com.example.john.myfirstapp;

import android.content.Intent;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TextView;


public class DisplayMessageActivity extends ActionBarActivity {

    Bundle bundle1;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_display_message);
        Log.i("debug", " you reach display1");
        Intent intent =this.getIntent();
        Log.i("debug", " you reach display2");
        bundle1 = intent.getExtras();
        String json = bundle1.getString("json");
        Log.i("debug", ""+json);
        String keyword = bundle1.getString("keyword");
        Log.i("debug", ""+keyword);
        Log.i("debug", " you reach display3");
        // Set the text view as the activity layout
        TextView show_validation = (TextView) findViewById(R.id.view_json);
        Log.i("debug", " you reach display4");
        show_validation.setText("lalala");
        Log.i("debug", " you reach display5");
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
