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
import android.widget.ImageView;
import android.widget.TextView;

import org.json.JSONObject;
import org.w3c.dom.Text;

import java.io.InputStream;
import java.util.Objects;


public class DisplayMessageActivity extends ActionBarActivity {
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
            Log.i("debug","display image");
            bmImage.setImageBitmap(result);
        }
    }

    Bundle bundle;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_display_message);
        Intent intent =this.getIntent();
        bundle = intent.getExtras();
        String json_string = bundle.getString("json");
        final String[] img_url = new String[5];
        final String[] item_url = new String[5];
        int cycle_num;
        final String[] item = new String[5];
        final String[] title = new String[5];
        final String[] price = new String[5];
        final String[] ship_cost = new String[5];
        final String[] item_location = new String[5];
        final String[] categoryName = new String[5];
        final String[] condition = new String[5];
        final String[] buying_format = new String[5];
        final String[] user_name = new String[5];
        final String[] feedback_score = new String[5];
        final String[] positive_feedback = new String[5];
        final String[] facebook_rating = new String[5];
        final String[] top_rating = new String[5];
        final String[] feedback_rating = new String[5];
        final String[] top_rated = new String[5];
        final String[] store = new String[5];
        final String[] shipping_type = new String[5];
        final String[] handling_time = new String[5];
        final String[] shipping_locations = new String[5];
        final String[] expedited_shipping = new String[5];
        final String[] one_day_shipping = new String[5];
        final String[] return_accepted = new String[5];
        //String item[5];
        //int i=0;
        try {
            JSONObject json_obj = new JSONObject(json_string);
            String result_count = json_obj.getString("resultCount");
            if( Integer.parseInt( result_count ) >5){
                cycle_num = 5;
            }
            else{
                cycle_num = Integer.parseInt( result_count );
            }
            for(int i =0; i<cycle_num;i++){
                item[i] = "item"+i;
                JSONObject item_i = json_obj.getJSONObject(item[i]);
                JSONObject BI = item_i.getJSONObject("basicInfo");
                item_url[i] = BI.getString("viewItemURL");
                title[i] = BI.getString("title");
                item_location[i]= BI.getString("location");
                img_url[i] = BI.getString("pictureURLSuperSize");
                price[i] = BI.getString("convertedCurrentPrice");
                ship_cost[i] = BI.getString("shippingServiceCost");
                categoryName[i] = BI.getString("categoryName");
                condition[i] = BI.getString("conditionDisplayName");
                buying_format[i] = BI.getString("listingType");

                JSONObject SI = item_i.getJSONObject("sellerInfo");
                user_name[i] = SI.getString("sellerUserName");
                feedback_score[i] = SI.getString("feedbackScore");
                positive_feedback[i] = SI.getString("positiveFeedbackPercent");
                feedback_rating[i] = SI.getString("feedbackRatingStar");
                top_rated[i] = SI.getString("topRatedSeller");
                store[i] = SI.getString("sellerStoreName"); if(store[i].equals("")){store[i]="N/A";}

                JSONObject SP = item_i.getJSONObject("shippingInfo");
                shipping_type[i] = SP.getString("shippingType");
                handling_time[i] = SP.getString("handlingTime");
                shipping_locations[i] = SP.getString("shipToLocations");
                expedited_shipping[i]= SP.getString("expeditedShipping");
                one_day_shipping[i] = SP.getString("oneDayShippingAvailable");
                return_accepted[i] = SP.getString("returnsAccepted");
                if( ship_cost[i].equals("0.0") ){
                    ship_cost[i] = "FREE";
                }
                if(img_url[i].equals("") ){
                    img_url[i] = BI.getString("galleryURL");
                }
            }
        }
        catch(Exception e){}

        //keywords
        String keyword = "Result for '"+bundle.getString("keyword") +"'";
        TextView show_keyword = (TextView) findViewById(R.id.view_keyword);
        show_keyword.setText(keyword);
        //item 0
        final TextView title0 = (TextView) findViewById(R.id.title0);
        title0.setText(title[0]);
        title0.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent0 = new Intent(DisplayMessageActivity.this,View_detail.class);
                Bundle bundle0 =new Bundle();
                bundle0.putString("title",title[0]);
                bundle0.putString("item_url",item_url[0]);
                bundle0.putString("price",price[0]);
                bundle0.putString("ship_cost",ship_cost[0]);
                bundle0.putString("item_location",item_location[0]);
                bundle0.putString("image_url",img_url[0]);

                bundle0.putString("categoryName",categoryName[0]);
                bundle0.putString("condition",condition[0]);
                bundle0.putString("buying_format",buying_format[0]);
                bundle0.putString("user_name",user_name[0]);
                bundle0.putString("feedback_score",feedback_score[0]);
                bundle0.putString("positive_feedback",positive_feedback[0]);
                bundle0.putString("feedback_rating",feedback_rating[0]);
                bundle0.putString("store",store[0]);
                bundle0.putString("shipping_type",shipping_type[0]);
                bundle0.putString("handling_time",handling_time[0]);
                bundle0.putString("shipping_loactions",shipping_locations[0]);
                bundle0.putString("expedited_shipping",expedited_shipping[0]);
                bundle0.putString("one_day_shipping",one_day_shipping[0]);
                bundle0.putString("return_accepted",return_accepted[0]);

                intent0.putExtras(bundle0);
                startActivity(intent0);
            }
        });
        TextView show_price = (TextView) findViewById(R.id.price0);
        show_price.setText("Price:$"+price[0]+"("+ship_cost[0]+" Shipping)" );
        ImageView image_view0 = (ImageView) findViewById(R.id.image0 );
        image_view0.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse( item_url[0] ));
                startActivity(browserIntent);
            }
        });
        DownloadImageTask download_img0 = new DownloadImageTask(image_view0);
        download_img0.execute(img_url[0]);
        //item1
        final TextView title1 = (TextView) findViewById(R.id.title1);
        title1.setText(title[1]);
        title1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent1 = new Intent(DisplayMessageActivity.this,View_detail.class);
                Bundle bundle1 =new Bundle();
                bundle1.putString("title",title[1]);
                intent1.putExtras(bundle1);
                startActivity(intent1);
            }
        });
        TextView show_price1 = (TextView) findViewById(R.id.price1);
        show_price1.setText("Price:$"+price[1]+"("+ship_cost[1]+" Shipping)" );
        ImageView image_view1 = (ImageView) findViewById(R.id.image1 );
        image_view1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse( item_url[1] ));
                startActivity(browserIntent);
            }
        });
        DownloadImageTask download_img1 = new DownloadImageTask(image_view1);
        download_img1.execute(img_url[1]);
/*        TextView title1 = (TextView) findViewById(R.id.title1);
        title1.setText(title[1]);
        ImageView image_view1 = (ImageView) findViewById(R.id.image1 );
        image_view1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse( item_url[1] ));
                startActivity(browserIntent);
            }
        });
        DownloadImageTask download_img1 = new DownloadImageTask(image_view1);
        download_img1.execute(img_url[1]);
*/
        //TextView test_view = (TextView) findViewById(R.id.view_test);
        //test_view.setText(img_url);
//        ImageView image_view = (ImageView) findViewById(R.id.image1);
//        DownloadImageTask download_img = new DownloadImageTask(image_view);
//        download_img.execute(img_url);


        //
        //TextView show_json = (TextView) findViewById(R.id.view_json);
        //show_json.setText(json);
        //TextView test_view = (TextView) findViewById(R.id.view_test);
        //test_view.setText(json_string);
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
