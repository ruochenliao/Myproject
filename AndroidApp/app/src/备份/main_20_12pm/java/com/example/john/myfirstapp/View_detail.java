package com.example.john.myfirstapp;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import java.io.InputStream;


public class View_detail extends ActionBarActivity {
    public class DownloadImageTask extends AsyncTask<String, Void, Bitmap> {
        ImageView bmImage;

        public DownloadImageTask(ImageView bmImage) {
            this.bmImage = bmImage;
        }

        protected Bitmap doInBackground(String... urls) {
            String urldisplay = urls[0];
            Bitmap mIcon11 = null;
            try {
                InputStream in = new java.net.URL(urldisplay).openStream();
                mIcon11 = BitmapFactory.decodeStream(in);
            } catch (Exception e) {
                Log.e("Error", e.getMessage());
                e.printStackTrace();
            }
            return mIcon11;
        }

        protected void onPostExecute(Bitmap result) {
            //Log.i("debug","display image");
            bmImage.setImageBitmap(result);
        }
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_view_detail);
        Bundle bundle;
        Intent intent =this.getIntent();
        bundle = intent.getExtras();
        String title = bundle.getString("title");
        String image_url = bundle.getString("image_url");
        String price = bundle.getString("price");
        String ship_cost = bundle.getString("ship_cost");
        String item_location = bundle.getString("item_location");
        final String item_url = bundle.getString("item_url");
        ImageView image_view = (ImageView)findViewById(R.id.image0);
        DownloadImageTask download_img = new DownloadImageTask(image_view);
        download_img.execute(image_url);

        TextView show_title = (TextView)findViewById(R.id.title);
        TextView show_price = (TextView)findViewById(R.id.price);
        TextView show_item_location = (TextView)findViewById(R.id.item_location);
        ImageView top_rated_img = (ImageView)findViewById(R.id.top_rated);
        ImageView facebook_icon = (ImageView)findViewById(R.id.facebook_icon);

        Button show_buy_now = (Button)findViewById(R.id.buy_now);
        show_title.setText(title);
        show_price.setText( "Price:$"+price+"("+ship_cost+" Shipping)" );
        show_item_location.setText(item_location);
        top_rated_img.setImageResource(R.drawable.toprated );
        facebook_icon.setImageResource(R.drawable.fb);

        show_buy_now.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(item_url));
                startActivity(browserIntent);
            }
        });
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_view_detail, menu);
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
